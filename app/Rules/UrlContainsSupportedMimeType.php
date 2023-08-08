<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class UrlContainsSupportedMimeType implements ValidationRule
{
    public function __construct(
        protected array $mimeTypes
    )
    {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::head($value);
        $contentType = $response->header('Content-Type');

        if($contentType === '' || !in_array($contentType, ['audio/mpeg', 'audio/wav'])) {
            $fail('Wrong content type');
        }
    }
}
