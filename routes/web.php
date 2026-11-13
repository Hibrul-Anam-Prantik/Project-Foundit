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
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
 * Feature modules are auto-loaded from routes/modules/*.php so each team
 * member owns a separate route file without merge conflicts in web.php.
 */
foreach (glob(__DIR__ . '/modules/*.php') ?: [] as $moduleRouteFile) {
    require $moduleRouteFile;
}
