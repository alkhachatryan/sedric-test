<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Models\Audio;
use Aws\Exception\AwsException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Aws\Credentials\Credentials;
use Aws\TranscribeService\TranscribeServiceClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Transcriber implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Audio $audio)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->audio->setStatus(Status::PROCESSING_BY_QUEUE);
        $s3Url = $this->uploadFile();
        $this->transcribe($s3Url);
    }

    protected function uploadFile(): string
    {
        $filePath = date('Y-m') . DIRECTORY_SEPARATOR . $this->audio->request_id;

        $file = Http::get($this->audio->audio_url);
        Storage::put($filePath, $file->body());

        return Storage::url($filePath);
    }

    protected function transcribe(string $s3Url)
    {
        $credentials = new Credentials(config('services.aws.key'), config('services.aws.secret'));

        $transcribeClient = new TranscribeServiceClient([
            'version' => 'latest',
            'region' => 'eu-central-1',
            'credentials' => $credentials,
        ]);

        $params = [
            'LanguageCode' => 'en-US',
            'Media' => [
                'MediaFileUri' => $s3Url,
            ],
            'TranscriptionJobName' => $this->audio->request_id
        ];

        try {
            $transcribeClient->startTranscriptionJob($params);

            $this->audio->setStatus(Status::PROCESSING_BY_AWS);
        } catch (AwsException $e) {
            // Handle exceptions
            echo "Error: " . $e->getMessage();
        }
    }

    public function uniqueId(): string
    {
        return $this->audio->request_id;
    }
}
