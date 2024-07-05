<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\MembersController;
use App\Http\Controllers\Api\BorrowsController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('books', [BooksController::class,'index']);
// Route::get('books/{id}', [BooksController::class,'show']);
// Route::post('books', [BooksController::class,'store']);
// Route::put('books/{id}', [BooksController::class,'update']);
// Route::delete('books/{id}', [BooksController::class,'destroy']);

Route::apiResource('books', BooksController::class);
Route::apiResource('members', MembersController::class);

Route::get('borrows', [BorrowsController::class,'index']);
Route::get('borrows/{id}', [BorrowsController::class,'show']);
Route::post('borrows', [BorrowsController::class,'store']);
Route::put('borrows/{id}', [BorrowsController::class,'update']);