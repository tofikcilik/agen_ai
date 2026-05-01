<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVillageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'district_id' => ['required', 'exists:districts,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('villages', 'code')],
            'name' => ['required', 'string', 'max:150'],
        ];
    }
}
