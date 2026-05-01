<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'village_id' => ['required', 'exists:villages,id'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'address_detail' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'meter_number' => ['nullable', 'string', 'max:50', Rule::unique('customers', 'meter_number')->ignore($customerId)],
            'status' => ['nullable', 'in:active,inactive'],
            'tariff_per_m3' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
