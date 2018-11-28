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

Route::get('/node-calculation', function() {
    return view('node-calculation');
});

Route::get('/tree/roots', 'NodeController@fetchRoots');
Route::post('/tree/descendants', 'NodeController@fetchDescendants');

Route::post('/node/delete-by-id', 'NodeController@deleteById');
Route::post('/node/random/node', 'NodeController@randomNode');
Route::post('/node/random/leaf', 'NodeController@randomLeaf');
Route::post('/node/append', 'NodeController@appendNode');
Route::post('/node/copy', 'NodeController@copyNode');