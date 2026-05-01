<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MeterReadingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Air Bersih Management API',
        'status' => 'ok',
        'version' => '0.1.0',
        'docs' => '/docs/api.md',
    ]);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'backend-api',
    ]);
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('customers', CustomerController::class);
    Route::get('meter-readings/monthly', [MeterReadingController::class, 'monthlyIndex']);
    Route::post('meter-readings', [MeterReadingController::class, 'store'])->middleware('role:petugas_lapangan,desa');
    Route::get('meter-readings', [MeterReadingController::class, 'index']);

    Route::get('bills', [BillController::class, 'index']);
    Route::post('bills/generate', [BillController::class, 'generate'])->middleware('role:desa,kecamatan');
    Route::get('bills/{bill}', [BillController::class, 'show']);

    Route::get('payments', [PaymentController::class, 'index']);
    Route::post('payments', [PaymentController::class, 'store'])->middleware('role:petugas_lapangan,desa');

    Route::get('complaints', [ComplaintController::class, 'index']);
    Route::post('complaints', [ComplaintController::class, 'store']);
    Route::patch('complaints/{complaint}/status', [ComplaintController::class, 'updateStatus']);

    Route::get('reports/financial-summary', [ReportController::class, 'financialSummary']);
    Route::get('reports/arrears', [ReportController::class, 'arrears']);
    Route::get('reports/usage', [ReportController::class, 'usage']);
});
