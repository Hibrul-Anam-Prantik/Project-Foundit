<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - FoundIT Portal
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application,
| including webhooks and callbacks from the FastAPI AI Microservice.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
