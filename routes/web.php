<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromptController;

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

Route::get('/', [PromptController::class, 'index'])->name('home');
Route::get('/start', [PromptController::class, 'start']);
Route::post('/start/message', [PromptController::class, 'message']);



