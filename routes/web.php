<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/donate', [PaymentController::class, 'processDonation']);
Route::get('/donation/thank-you', [PaymentController::class, 'thankYou']);
