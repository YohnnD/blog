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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


/*
|--------------------------------------------------------------------------
| RUTAS DE LOGIN
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');//iniciar sesion
    Route::post('logout', 'AuthController@logout');//cerrar sesion
    Route::post('refresh', 'AuthController@refresh');//te retorna un nuevo token
    Route::post('me', 'AuthController@me');//te devuelves lo datos del usuario que inicio sesión
});


/*
|--------------------------------------------------------------------------
| RUTAS DE USER
|--------------------------------------------------------------------------
*/
Route::resource('users','UserController');
Route::resource('topics','TopicController');
Route::resource('notepads','NotepadsController');
Route::resource('comments','CommentController');



/*
|        |    POST   | api/users             | users.store   | App\Http\Controllers\UserController@store   --CREA UN NUEVO USUARIO
         |    GET    | api/users/{user}      | users.show    | App\Http\Controllers\UserController@show    --DEVUELVE UN USUARIO
|        | PUT|PATCH | api/users/{user}      | users.update  | App\Http\Controllers\UserController@update  --ACTUALIZA EL USUARIO
|        | DELETE    | api/users/{user}      | users.destroy | App\Http\Controllers\UserController@destroy --ELIMINA EL USUARIO
*/
