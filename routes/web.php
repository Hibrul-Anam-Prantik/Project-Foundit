<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;

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
 * R7 Categories: generates index/create/store/edit/update/destroy routes.
 * show() is excluded because R7 has no category detail page.
 */
Route::resource('categories', CategoryController::class)->except(['show']);

/*
 * Note for team: Feature routes for Sprint 1 (item reporting, browsing,
 * claims, and inventory management) will be attached here.
 */
