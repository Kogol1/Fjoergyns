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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/commands/{name}/{server}',[
    'as' => 'commands', 'uses' => 'ApiController@getCommands'
]);

$router->get('/tpa-kills/{killer}/{victim}/{server}',[
    'as' => 'tpa-kills', 'uses' => 'ApiController@getTpaKills'
]);

$router->post('/test-api', [
    'as' => 'test-api', 'uses' => 'ApiController@post'
]);
