<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Not Found'], 404);
});
