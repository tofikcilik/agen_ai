<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');

        $districts = $this->scopeDistricts(
            District::query()->orderBy('name'),
            $user
        )->get();

        return response()->json($districts);
    }
}
