<?php

use App\Http\Controllers\RecognitionController;
use App\Http\Controllers\UploadController;
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

Route::get('/', [RecognitionController::class, 'index']);
Route::get('/remove-file/{rec}', [RecognitionController::class, 'removeFile']);
Route::get('/download-text/{rec}', [RecognitionController::class, 'downloadText'])->name('download-text');

Route::post('/upload', [UploadController::class, 'uploadAudio']);
