<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

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

Route::get('/', function () {
    return redirect()->route('dashboard');
    // return view('welcome');
})->middleware('auth');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['prefix' => '/members', 'middleware' => 'auth'], function () {
    Route::get('/index', [MemberController::class, 'index'])->name('members.index');
    Route::post('/create', [MemberController::class, 'store'])->name('members.store');
    Route::get('/download/{memberId}', [MemberController::class, 'documentDownload'])->name('members.download');
});