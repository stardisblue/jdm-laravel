<?php

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

Route::get('/node/{word}/card', "AjaxController@card");

Route::get('/node/{word}/{relationType}/{page}', function () {
    // récuperer les relations paginées
});

Route::post('/node/{word}',"AjaxController@ajaxUpdateAndGet");