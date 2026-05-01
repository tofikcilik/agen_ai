<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVillageRequest;
use App\Http\Requests\UpdateVillageRequest;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\JsonResponse;

class VillageController extends Controller
{
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');

        $villages = $this->scopeVillages(
            Village::query()->with('district')->orderBy('name'),
            $user
        )->get();

        return response()->json($villages);
    }

    public function store(StoreVillageRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $district = District::findOrFail($request->integer('district_id'));
        $this->ensureDistrictModelAccess($user, $district);

        $village = Village::create($request->validated());

        return response()->json($village->load('district'), 201);
    }

    public function update(UpdateVillageRequest $request, Village $village): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $this->ensureVillageModelAccess($user, $village);

        $district = District::findOrFail($request->integer('district_id'));
        $this->ensureDistrictModelAccess($user, $district);

        $village->update($request->validated());

        return response()->json($village->fresh('district'));
    }

    public function destroy(Village $village): JsonResponse
    {
        $user = request()->user()->loadMissing('role');
        $this->ensureVillageModelAccess($user, $village);

        $village->delete();

        return response()->json(['message' => 'Data desa berhasil dihapus.']);
    }
}
