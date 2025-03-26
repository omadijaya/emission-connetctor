<?php

declare(strict_types=1);

use App\Http\Controllers\V1\EmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function (Request $request) {
    return 'pong';
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/emission/flight', [EmissionController::class, 'getFlightEmission']);
    Route::post('/emission/hotel', [EmissionController::class, 'getHotelEmission']);
    Route::post('/emission/train', [EmissionController::class, 'getTrainEmission']);
});
