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
    return $app->version();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('person', 'App\Api\Controllers\PersonController@index');
    $api->get('person/{id}', 'App\Api\Controllers\PersonController@show');
    $api->get('search', 'App\Api\Controllers\SearchController@search');
    $api->get('relations/{relation}/{id}', 'App\Api\Controllers\RelationsController@relation');
});
