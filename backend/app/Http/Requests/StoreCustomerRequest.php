<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'village_id' => ['required', 'exists:villages,id'],
            'customer_number' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'meter_number' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
            'tariff_per_m3' => ['required', 'numeric', 'min:0'],
        ];
    }
}
