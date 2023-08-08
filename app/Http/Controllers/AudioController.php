<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAudioRequest;
use App\Models\Audio;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


class AudioController extends Controller
{
    public function index(): JsonResponse
    {
        return responseJson(Audio::query()->paginate(100));
    }

    public function create(CreateAudioRequest $request): JsonResponse
    {
        $sentences = collect($request->input('sentences'))->map(function (string $sentence) {
            return [
              'plain_text' => $sentence,
              'was_present' => false,
              'start_word_index' => null,
              'end_word_index' => null,
            ];
        });

        $audio = Audio::create([
            'audio_url' => $request->input('audio_url'),
            'sentences' => $sentences->toArray(),
            'request_id' => Str::uuid()
        ]);

        return responseJson([
            'body' => [
                'request_id' => $audio->request_id,
                'message' => 'Your request was accepted successfully',
            ]
        ], Response::HTTP_CREATED);
    }
}
