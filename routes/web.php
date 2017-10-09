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

Route::get('/search/', function () {
// rechercher un mot
});

Route::get('/node/{word}', function ($word) {
    // afficher un mot
    //if (Cache::get($word)) {
    //    $parsed = Cache::get($word);
    //} else {
    $response = FetchWord::fetch(utf8_decode($word));
    $parsed = WordParser::parse($response['out']);
    $parsed->setName($word);
    // Cache::put($word, $parsed, 60);
    //}

    return view('node.single', ["parsed" => $parsed]);
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