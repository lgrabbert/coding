<?php

declare(strict_types=1);

namespace App\Http\Request;

use App\Enums\OrderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(OrderType::values())],
            'name' => ['required', 'string']
        ];
    }
}
