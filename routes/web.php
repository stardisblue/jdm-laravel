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

Route::get("/", function () {
    return view('homepage');
});

Route::get('/search/', function () {
// rechercher un mot
});

Route::get('/node/{word}', function ($word) {
    // afficher un mot
    $parsed="";
    if (Cache::get($word)) {
        $parsed=Cache::get($word);
        }
    else {
        $response = \Meliblue\FetchWord::fetch(utf8_decode($word));
        $parsed = \Meliblue\WordParser::parse($response['out']);
        Cache::put($word, $parsed, 60);
    }
    return view('welcome', ["parsed" => $parsed]);
});


Route::get('/api/node/{word}/{relationType}/{page}', function () {
// récuperer les relations
});

Route::get('/api/node/{word}/card', function () {
// afficher un card du mot
});

Route::post('/api/node/{word}', function () {
    // mettre à jour le cache
});