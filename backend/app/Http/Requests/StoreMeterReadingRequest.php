<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeterReadingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'reading_month' => ['required', 'date_format:Y-m'],
            'previous_value' => ['required', 'integer', 'min:0'],
            'current_value' => ['required', 'integer', 'gte:previous_value'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
