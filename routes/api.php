<?php

use App\Http\Controllers\PdfProtectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/protect',[PdfProtectionController::class, 'protect']);