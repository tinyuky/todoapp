<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('todos/{paginate}', 'TodoController@getall');
Route::get('todo/{id}', 'TodoController@get');
Route::post('add', 'TodoController@add');
Route::post('update', 'TodoController@update');
Route::get('delete/{id}', 'TodoController@delete');

