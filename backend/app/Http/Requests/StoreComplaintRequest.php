<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'category' => ['required', 'in:kebocoran,air_mati,tagihan,kualitas_air,lainnya'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
        ];
    }
}
