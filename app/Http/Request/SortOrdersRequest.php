<?php

declare(strict_types=1);

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class SortOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'in:asc,desc'],
            'date' => ['nullable', 'in:asc,desc']
        ];
    }
}
