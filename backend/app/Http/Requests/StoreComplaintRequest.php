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
            'customer_id' => ['nullable', 'exists:customers,id'],
            'village_id' => ['nullable', 'exists:villages,id'],
            'reporter_name' => ['required', 'string', 'max:150'],
            'reporter_phone' => ['nullable', 'string', 'max:30'],
            'category' => ['nullable', 'in:kebocoran,air_mati,tagihan,kualitas_air,lainnya'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'disturbance_location' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
