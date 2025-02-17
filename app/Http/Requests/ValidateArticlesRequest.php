<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateArticlesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string|max:255',
            'from_date' => 'nullable|date|before_or_equal:today',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'category_id' => 'nullable|integer|exists:news_categories,id',
            'platform_id' => 'nullable|integer|exists:news_platforms,id',
            'source' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1'
        ];
    }
}
