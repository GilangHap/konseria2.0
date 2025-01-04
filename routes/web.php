<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use Illuminate\Auth\Middleware\Authenticate;

// Halaman utama dan tentang
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
});

// Rute untuk event
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Rute untuk pembayaran
Route::get('/payment/{event}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/generate-snap-token', [PaymentController::class, 'generateSnapToken'])->name('payment.generateSnapToken');

// Rute untuk transaksi
Route::get('/transaction/{uuid}', [PaymentController::class, 'transactionDetails'])->name('transaction.details');

// Rute untuk verifikasi tiket
Route::post('/tickets/verify', [TicketController::class, 'verify'])->name('ticket.verify');

// Rute untuk admin panel (Filament)
Route::middleware(['auth', Authenticate::class])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('filament.resources.events.create');
    })->name('filament.resources.events.create');
});
