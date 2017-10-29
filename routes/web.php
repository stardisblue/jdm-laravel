<?php

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

use Meliblue\FetchWord;
use Meliblue\WordParser;

Route::get("/", function () {
    return view('welcome');
});

Route::get('/search/', 'SearchController@search' );

Route::get('/node/{word}', 'NodeController@display');

Route::get('/api/node/{word}/{relationType}/{page}', function () {
// récuperer les relations
});

Route::post('/api/node/{word}', function () {
    // mettre à jour le cache
});