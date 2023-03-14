<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/', function () use ($router) {
    return $router->app->version();
});

//AUTH
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->group(['middleware' => 'auth','prefix' => 'invitation'], function () use ($router) {
    $router->get('/', 'InvitationController@index');
    $router->get('/{id}', 'InvitationController@show');
    $router->put('/{id}', 'InvitationController@update');
    $router->post('/', 'InvitationController@store');
    $router->delete('/{id}', 'InvitationController@destroy');
});


$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/user', 'AuthController@index');

    $router->post('/event', 'EventController@store');    
    $router->put('/event/{id}', 'EventController@update');
    $router->delete('/event/{id}', 'EventController@destroy');

    $router->post('/gift', 'GiftController@store');
    $router->put('/gift/{id}', 'GiftController@update');
    $router->delete('/gift/{id}', 'GiftController@destroy');

    
    $router->put('/message/{id}', 'MessageController@update');
    $router->delete('/message/{id}', 'MessageController@destroy');

    

});
$router->post('/image/upload', 'GalleryController@upload');

$router->get('/invitation-by-slug/{slug}', 'InvitationController@showBySlug');
$router->get('/invitation/{id}/messages', 'InvitationController@getMessages');
$router->post('/message', 'MessageController@store');