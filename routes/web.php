<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use Illuminate\Auth\Middleware\Authenticate;

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/about', function () {return view('about');});

// Route::get('/admin', function () {return redirect()->route('filament.resources.events.create');})->name('filament.resources.events.create');

Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/payment/{event}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment', [PaymentController::class, 'process'])->name('payment.process');

Route::get('/transaction/{uuid}', [PaymentController::class, 'transactionDetails'])->name('transaction.details');

Route::post('/tickets/verify', [TicketController::class, 'verify'])->name('ticket.verify');
