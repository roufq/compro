<?php

namespace App\Rules;

use App\Models\Portfolio;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class MediaUrlList implements ValidationRule
{
    public function __construct(public bool $youtube = false) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $urls = array_values(array_filter(array_map('trim', preg_split('/\R/', $value) ?: [])));

        if (count($urls) > ($this->youtube ? 20 : 100)) {
            $fail($this->youtube ? 'Maksimal 20 video per IP.' : 'Maksimal 100 foto per IP.');

            return;
        }

        foreach ($urls as $url) {
            $scheme = parse_url($url, PHP_URL_SCHEME);
            if (strlen($url) > 2048 || ! filter_var($url, FILTER_VALIDATE_URL) || ! in_array(strtolower(is_string($scheme) ? $scheme : ''), ['http', 'https'], true)) {
                $fail('Setiap baris harus berisi satu URL HTTP atau HTTPS yang valid.');

                return;
            }

            if ($this->youtube && Portfolio::youtubeIdFromUrl($url) === null) {
                $fail('Setiap baris video harus berisi tautan video YouTube yang valid.');

                return;
            }
        }
    }
}
