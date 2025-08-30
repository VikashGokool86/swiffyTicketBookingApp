<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Add this route for the create support ticket view
    Route::get('/support-tickets/create', [TicketController::class, 'create'])->name('support-tickets.create');

    Route::resource('tickets', TicketController::class);
});
