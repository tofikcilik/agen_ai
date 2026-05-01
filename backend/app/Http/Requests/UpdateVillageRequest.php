<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateVillageRequest extends StoreVillageRequest
{
    public function rules(): array
    {
        $villageId = $this->route('village')?->id ?? $this->route('village');

        return [
            'district_id' => ['required', 'exists:districts,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('villages', 'code')->ignore($villageId)],
            'name' => ['required', 'string', 'max:150'],
        ];
    }
}
