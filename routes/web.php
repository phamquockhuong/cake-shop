<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'as'=>'admin.'], function() {
    Route::group(['middleware' => 'admin.guest'], function() {
        Route::get('/login',[AdminLoginController::class,'index'])->name('login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function() {
        //dashboard
        Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
        Route::get('/logout',[DashboardController::class,'logout'])->name('logout');

        //category
        Route::resource('categories', CategoryController::class);
    });
});
