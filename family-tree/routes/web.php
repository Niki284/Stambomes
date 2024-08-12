<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Search routes
    Route::get('/searches', [PeopleController::class, 'search'])->name('peoples.search');
    // People routes
    Route::get('/peoples', [PeopleController::class, 'index'])->name('peoples.index');
    Route::get('/peoples/create', [PeopleController::class, 'create'])->name('peoples.create');
    Route::post('/peoples', [PeopleController::class, 'store'])->name('peoples.store');
    Route::get('/api/users', [PeopleController::class, 'getUsers']);
    Route::get('/peoples/{id}', [PeopleController::class, 'show'])->name('peoples.show');
    Route::get('/peoples/{id}/edit', [PeopleController::class, 'edit'])->name('peoples.edit');
    Route::put('/peoples/{id}', [PeopleController::class, 'update'])->name('peoples.update');
    Route::delete('/peoples/{id}', [PeopleController::class, 'destroy'])->name('peoples.destroy');
    // Route::delete('/peoples/{id}', [PeopleController::class, 'destroy'])->name('peoples.destroy');

    // History routes
    Route::get('peoples/{id}/history/create', [HistoryController::class, 'create'])->name('histories.create');
    Route::post('peoples/{id}/history', [HistoryController::class, 'store'])->name('histories.store');
    Route::get('peoples/{id}/history/edit', [HistoryController::class, 'edit'])->name('histories.edit');
    Route::put('peoples/{id}/history', [HistoryController::class, 'update'])->name('histories.update');
    Route::delete('peoples/{id}/history', [HistoryController::class, 'destroy'])->name('histories.destroy');

    // Gallery routes

    Route::get('peoples/{id}/gallery/create', [GalleryController::class, 'create'])->name('galleries.create');
    Route::post('peoples/{id}/gallery', [GalleryController::class, 'store'])->name('galleries.store');
    //Route::delete('peoples/{id}/gallery/{galleryId}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
    Route::delete('peoples/{personId}/gallery/{galleryId}', [GalleryController::class, 'destroy'])->name('galleries.destroy');



    Route::resource('countries', CountryController::class);
});

require __DIR__ . '/auth.php';
