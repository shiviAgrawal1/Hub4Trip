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


/*************Auth********************/
$router->get('/user' , 'UserController@showAllUsers');
$router->get('/user/{id}' , 'UserController@showOneUser');
$router->post('/user_register' , 'UserController@createUser');
$router->post('/user_login' , 'UserController@login');
$router->put('/update_user/{id}' , 'UserController@updateUser');
$router->delete('/delete_user/{id}' , 'UserController@deleteUser');
/*************************************/


/********Trip**************************/
$router->post('/create_trip' , 'TripController@createTrip');
$router->get('/source/{source}/destination/{destination}', ['uses' => 'TripController@search']);
$router->delete('/delete_trip/{id}',['uses' => 'TripController@delete']);
$router->put('/update_trip/{id}',['uses' => 'TripController@update']);
    


    $router->get('tripparticipation/{id}', ['uses' => 'Trip_ParticipationController@search']);
    $router->post('tripparticipation', ['uses' => 'Trip_ParticipationController@create']);
    $router->delete('tripparticipation/{id}', ['uses' => 'Trip_ParticipationController@delete']);


    $router->delete('schedule/{id}', ['uses' => 'Trip_scheduleController@delete']);
    $router->post('schedule', ['uses' => 'Trip_scheduleController@create']);
    $router->put('schedule/{id}', ['uses' => 'Trip_scheduleController@modify']);
    
    $router->delete('checkpoint/{id}', ['uses' => 'CheckpointController@delete']);
    $router->post('checkpoint', ['uses' => 'CheckpointController@create']);
    $router->put('checkpoint/{id}', ['uses' => 'CheckpointController@modify']);


