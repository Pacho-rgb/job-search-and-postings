<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// All listings
Route::get('/', [ListingController::class, 'index'])->name('home');

// Show the create form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// Store the listing data from the create form
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// Show the edit form based on the listing you want to update
// Route-model binding
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// Update the data based on the info posted from the edit form
// Route-model binding
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// Delete a lisitng based on the id
// Route-model binding
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

// Manage one's listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Single listing
// Route-model binding
Route::get('/listings/{listing}', [ListingController::class, 'show']);

 
// Authentication routes
// Show the register form
Route::get('/register', [UserController::class, 'register'])->middleware('guest');

// Store the data from the register form
Route::post('/users', [UserController::class, 'store']);

// Log the user out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show the login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Login the user based on the data provided in the form
Route::post('users/authenticate', [UserController::class, 'authenticate']);