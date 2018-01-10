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
// idNode and page are autochecked in RouteServiceProvider

Route::get('/card', "AjaxController@card");

Route::get('/node/{idNode}/relation-type/{idRelationType}/{way}/{page?}', 'AjaxController@getNodeRelation')
    ->where([
        'idRelationType' => "[0-9]+",
        'way' => "in|out",
    ]);


Route::get('/node/{idNode}/relation-type/{idRelationType}/search/{page?}',
    'AjaxController@searchRelationInRelationType')
    ->where('idRelationType', '[0-9]+');

Route::get('/node/{idNode}/search', 'AjaxController@searchRelationInNode');

Route::get('/node/search', "AjaxController@autocompleteNode");

Route::post('/node/{idNode}', "AjaxController@ajaxUpdateAndGet");


