<?php

namespace App\Http\Requests;

use App\Rules\UrlContainsSupportedMimeType;
use Illuminate\Foundation\Http\FormRequest;

class CreateAudioRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'audio_url' => ['required', 'max:2000', new UrlContainsSupportedMimeType(['mp3', 'wav'])],
            'sentences' => ['required', 'array']
        ];
    }
}
