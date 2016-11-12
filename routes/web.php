<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return view('index');
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('person', 'App\Api\Controllers\PersonController@index');
    $api->get('person/{id}', 'App\Api\Controllers\PersonController@show');
    $api->get('search', 'App\Api\Controllers\SearchController@search');
    $api->get('relations/allpersons', 'App\Api\Controllers\RelationsController@allpersons');
    $api->get('relations/faction/{id}', 'App\Api\Controllers\RelationsController@faction');
    $api->get('relations/factions', 'App\Api\Controllers\RelationsController@factions');
});
