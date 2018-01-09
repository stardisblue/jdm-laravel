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

Route::get('/node/{idNode}/relation-type/{idRelationType}/{way}/{page}', 'AjaxController@getNodeRelation')
    ->where([
        'idNode' => '[0-9]+',
        'idRelationType' => "[0-9]+",
        'way' => "in|out",
        'page' => '[0-9]+',
    ]);


Route::get('/node/{idNode}/relation-type/{idRelationType}/{way}/search/{word}/{page}',
    'AjaxController@searchNodeRelation')
    ->where([
        'idNode' => '[0-9]+',
        'idRelationType' => "[0-9]+",
        'way' => "in|out",
        'page' => '[0-9]+',
    ]);

Route::post('/node/{word}', "AjaxController@ajaxUpdateAndGet");