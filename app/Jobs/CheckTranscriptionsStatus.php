<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Models\Audio;
use Aws\Credentials\Credentials;
use Aws\TranscribeService\TranscribeServiceClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckTranscriptionsStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Audio::whereStatus(Status::PROCESSING_BY_AWS)->chunk(1000, function (Collection $audios) {
            $audios->each(function (Audio $audio) {
                $credentials = new Credentials(config('services.aws.key'), config('services.aws.secret'));

                $transcribeClient = new TranscribeServiceClient([
                    'version' => 'latest',
                    'region' => 'eu-central-1',
                    'credentials' => $credentials,
                ]);

                $params = [
                    'TranscriptionJobName' => $audio->request_id
                ];

                $response = $transcribeClient->getTranscriptionJob($params);
                $data = $response->toArray();

                if($data['TranscriptionJob']['TranscriptionJobStatus'] === 'COMPLETED') {
                    $transcriptUrl = $data['TranscriptionJob']['Transcript']['TranscriptFileUri'];
                    $audio->setStatus(Status::UPDATING_MODEL);
                    UpdateTranscription::dispatch($audio, $transcriptUrl);
                }
            });
        });
    }
}
