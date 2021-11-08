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

/*$router->get('/', function () use ($router) {
    return $router->app->version();
});*/


/** @var Rout $router */

$router->get('/', function() {
    return 'hello';
});

$router->group(['prefix'=>'api/v1/auth'], function() use($router){

    $router->post('/login', 'AuthController@getToken');
    $router->post('/register', 'AuthController@register');

});

$router->group(['prefix'=>'api/v1', 'middleware' => 'auth'], function() use($router) {

    $router->get('/groups', 'GroupsController@get');
    $router->get('/groups/{id}', 'GroupsController@getGroup');
    $router->patch('/groups/{id}', 'GroupsController@patchGroup');
    $router->post('/groups', 'GroupsController@post');
    $router->delete('/groups/{id}', 'GroupsController@delete');


    $router->get('/groups/{group_id}/users', 'GroupUsersController@get');
    $router->post('/groups/{group_id}/users', 'GroupUsersController@post');
    $router->delete('/groups/{group_id}/users/{user_email}', 'GroupUsersController@delete');

    $router->get('/groups/{id_group}/budgets', 'budgetsController@getBudgets');
    $router->post('/groups/{id_group}/budgets', 'budgetsController@postBudgets');
    $router->get('/groups/{id_group}/budgets/{id_budget}', 'budgetsController@deleteBudget');
    $router->post('/groups/{id_group}/budgets/{id_budget}', 'budgetsController@patchBudget');

    $router->get('/spents', 'SpentsController@getSpents');
    $router->get('/groups/{id_group}/budgets/{id_budget}/spents', 'SpentsController@getSpentsByBudget');
    $router->post('/groups/{id_group}/budgets/{id_budget}/spents', 'SpentsController@postSpent');
    $router->get('/groups/{id_group}/budgets/{id_budget}/spents/{id_spent}', 'SpentsController@deleteSpent');
    $router->post('/groups/{id_group}/budgets/{id_budget}/spents/{id_spent}', 'SpentsController@patchSpent');

    $router->get('/groups/{id_group}/budgets/{id_budget}/users', 'UserSpentsController@getUsers');
    $router->post('/groups/{id_group}/budgets/{id_budget}/users/{user_id}/{perm}', 'UserSpentsController@putUserPerm');


});
