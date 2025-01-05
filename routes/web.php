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
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');
Route::get('/payment/transaction/{transaction}', [PaymentController::class, 'showTransaction'])->name('payment.showTransaction');

// Rute untuk transaksi
Route::get('/transaction/{uuid}', [PaymentController::class, 'transactionDetails'])->name('transaction.details');

// Rute untuk download tiket
Route::get('/transaction/{uuid}/download', [PaymentController::class, 'downloadTicket'])->name('transaction.download');

// Rute untuk verifikasi tiket
Route::post('/tickets/verify', [TicketController::class, 'verify'])->name('ticket.verify');

