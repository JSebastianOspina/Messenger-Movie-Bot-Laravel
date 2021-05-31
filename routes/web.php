<?php

use Illuminate\Support\Facades\Route;
use App\Movies;

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

/*Route::get('/{genre}', function ($genre) {
    $movie = new Movies();
    return $movie->getMovieByGenre($genre);
});*/

Route::view('/privacy', 'privacy');

Route::get('/send/{genre}', function ($genre) {
    return $genre;
    $movie = new Movies();
    return $movie->getMovieByGenre($genre);
});


Route::get('/facebook/webhook', [\App\Http\Controllers\MessengerController::class, 'verifyMessenger']);
Route::post('/facebook/webhook', [\App\Http\Controllers\MessengerController::class, 'handleIncomingMessage']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
