<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Models\Audio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UpdateTranscription implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Audio $audio,
        protected string $transcriptUrl
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transcripContent = Str::lower(Http::get($this->transcriptUrl)->json('results.transcripts.0.transcript'));
        $updatedSentences = $this->audio->sentences;

        foreach ($this->audio->sentences as $key => $sentence) {
            $startIndex = strpos($transcripContent, $sentence['plain_text']);

            if ($startIndex !== false) {
                $endIndex = $startIndex + strlen($sentence['plain_text']) - 1;

                $updatedSentences[$key]['was_present'] = true;
                $updatedSentences[$key]['start_word_index'] = $startIndex;
                $updatedSentences[$key]['end_word_index'] = $endIndex;
            }
        }

        $this->audio->sentences = $updatedSentences;
        $this->audio->status = Status::PROCESSED;
        $this->audio->save();
    }

    public function uniqueId(): string
    {
        return $this->audio->request_id;
    }
}
