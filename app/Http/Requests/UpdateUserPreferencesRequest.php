<?php

namespace App\Http\Requests;

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\NewsArticle;

class UpdateUserPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categories' => ['array', 'nullable'],
            'categories.*' => ['exists:news_categories,id'],

            'sources' => ['array', 'nullable'],
            'sources.*' => ['exists:news_articles,source'],

            'authors' => ['array', 'nullable'],
            'authors.*' => [
                function ($attribute, $value, $fail) {
                    $exists = NewsArticle::whereRaw("LOWER(author) LIKE ?", ["%" . strtolower($value) . "%"])->exists();
                    if (!$exists) {
                        $fail("The author '$value' does not exist in news articles.");
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'categories.*.exists' => 'One or more selected categories do not exist.',
            'sources.*.exists' => 'One or more selected sources do not exist.'
        ];
    }
}
