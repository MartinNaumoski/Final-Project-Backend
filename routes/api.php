<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
//Route::post('/addBooksBulk', 'BooksControler@addBooksBulk');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('/logout', 'AuthController@logout');

    Route::post('/addBook', 'BooksControler@addBook');

    Route::get('/getAllBooks', 'BooksControler@getAllBooks');
    Route::get('/getBook/{id}', 'BooksControler@getSpecificBooks');

    Route::get('/getFavorites','BooksControler@getFavorites');
    Route::post('/makeFavourite','BooksControler@makeFavouriteBook');

    Route::post('/reserveBook','BooksControler@reserveBook');
    Route::get('/getMyReservations','BooksControler@getMyReservation');
    Route::get('/getInternetCategory','BooksControler@getInternetCategory');
    Route::get('/getWebCategory','BooksControler@getWebDevelopmentCategory');
    Route::get('/getMicrosoftCategory','BooksControler@getMicrosoftCategory');
    Route::get('/getJavaCategory','BooksControler@getJavaCategory');

});
