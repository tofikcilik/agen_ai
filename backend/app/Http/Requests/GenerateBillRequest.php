<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reading_month' => ['required', 'date_format:Y-m'],
            'due_date' => ['required', 'date'],
        ];
    }
}
