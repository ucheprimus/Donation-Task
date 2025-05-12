<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/donation/process', [PaymentController::class, 'processDonation'])->name('donation.process');
Route::get('/donation/thank-you', [PaymentController::class, 'thankYou'])->name('donation.thank-you');