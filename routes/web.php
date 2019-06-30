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

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->get('/checklists/templates', 'TemplatesController@index');
$router->post('/checklists/templates', 'TemplatesController@store');
$router->get('/checklists/templates/{templateId}', 'TemplatesController@show');
$router->patch('/checklists/templates/{templateId}', 'TemplatesController@update');
$router->delete('/checklists/templates/{templateId}', 'TemplatesController@destroy');

$router->get('/checklists', 'ChecklistController@index');
$router->get('/checklists/{checklistId}', 'ChecklistController@show');
$router->post('/checklists', 'ChecklistController@store');
$router->patch('/checklists/{checklistId}', 'ChecklistController@update');
$router->delete('/checklists/{checklistId}', 'ChecklistController@destroy');

$router->get('checklists/{checklistId}/items', 'ItemsController@allitems');
$router->post('/checklists/{checklistId}/items', 'ItemsController@store');
$router->get('/checklists/{checklistId}/items/{itemId}', 'ItemsController@show');
$router->patch('/checklists/{checklistId}/items/{itemId}', 'ItemsController@update');
$router->delete('/checklists/{checklistId}/items/{itemId}', 'ItemsController@destroy');
$router->post('/checklists/complete', 'ItemsController@complete');
$router->post('/checklists/incomplete', 'ItemsController@incomplete');
$router->post('/checklists/{checklistId}/items/_bulk', 'ItemsController@bulk');