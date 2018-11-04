<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/smaller', function() {
    return view('small-tree');
});

Route::get('/tree', 'WelcomeController@getTree');
Route::post('/tree/add-root-child', 'WelcomeController@addRootChild');
Route::post('/tree/add-leaf', 'WelcomeController@addLeaf');
Route::post('/tree/delete-leaf', 'WelcomeController@deleteLeaf');
Route::post('/tree/delete-by-id', 'WelcomeController@deleteById');
Route::post('/tree/delete-node-with-children', 'WelcomeController@deleteNodeWithChildren');
Route::post('/tree/duplicate-by-id', 'WelcomeController@duplicateById');