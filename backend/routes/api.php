<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\ContactController;

Route::post('/auth/google', [AuthController::class, 'googleLogin']);
Route::post('/contact', [ContactController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Invoices
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail']);
    
    // Attendances
    Route::apiResource('attendances', AttendanceController::class);

    // Reports
    Route::get('/reports/attendance', [ReportController::class, 'attendance']);
    Route::get('/reports/invoices', [ReportController::class, 'invoices']);
});
