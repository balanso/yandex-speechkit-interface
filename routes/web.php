<?php

use App\Http\Controllers\RecognitionController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes([
	'register' => false, // Registration Routes...
	'reset' => false, // Password Reset Routes...
	'verify' => false, // Email Verification Routes...
]);

Route::middleware('auth')->group(function() {
	Route::get('/', [RecognitionController::class, 'index'])->name('index');
	Route::get('/remove-file/{rec}', [RecognitionController::class, 'removeFile']);
	Route::get('/download-text/{rec}', [RecognitionController::class, 'downloadText'])->name('download-text');

	Route::get('/clean-history', [RecognitionController::class, 'cleanHistory'])->name('clean-history');

	Route::post('/upload', [UploadController::class, 'uploadAudio']);

});
