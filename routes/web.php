<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes - FoundIT Portal
|--------------------------------------------------------------------------
|
| Entry routes for the FoundIT Campus Lost & Found System.
|
*/

// Display the login page as the default root landing page
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm']);

/*
 * Note for team: Feature routes for Sprint 1 (item reporting, browsing,
 * claims, and inventory management) will be attached here.
 */
