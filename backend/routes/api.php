<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\MeterReadingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\VillageController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/districts', [DistrictController::class, 'index']);
    Route::get('/villages', [VillageController::class, 'index']);
    Route::post('/villages', [VillageController::class, 'store'])->middleware('role:administrator,kecamatan');
    Route::put('/villages/{village}', [VillageController::class, 'update'])->middleware('role:administrator,kecamatan');
    Route::delete('/villages/{village}', [VillageController::class, 'destroy'])->middleware('role:administrator,kecamatan');

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
    Route::post('customers', [CustomerController::class, 'store'])->middleware('role:administrator,desa,kecamatan');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->middleware('role:administrator,desa,kecamatan');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->middleware('role:administrator,desa,kecamatan');

    Route::get('meter-readings/monthly', [MeterReadingController::class, 'monthlyIndex']);
    Route::post('meter-readings', [MeterReadingController::class, 'store'])->middleware('role:administrator,petugas_lapangan,desa');
    Route::get('meter-readings', [MeterReadingController::class, 'index']);

    Route::get('bills', [BillController::class, 'index']);
    Route::post('bills/generate', [BillController::class, 'generate'])->middleware('role:administrator,desa,kecamatan');
    Route::get('bills/{bill}', [BillController::class, 'show']);

    Route::get('payments', [PaymentController::class, 'index']);
    Route::post('payments', [PaymentController::class, 'store'])->middleware('role:administrator,petugas_lapangan,desa');

    Route::get('complaints', [ComplaintController::class, 'index']);
    Route::post('complaints', [ComplaintController::class, 'store']);
    Route::patch('complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->middleware('role:administrator,petugas_lapangan,desa');

    Route::get('reports/financial-summary', [ReportController::class, 'financialSummary'])->middleware('role:administrator,desa,kecamatan');
    Route::get('reports/arrears', [ReportController::class, 'arrears'])->middleware('role:administrator,desa,kecamatan');
    Route::get('reports/usage', [ReportController::class, 'usage'])->middleware('role:administrator,desa,kecamatan');
});
