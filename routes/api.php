<?php
use App\User;


Route::apiResource('/question','QuestionController');
Route::apiResource('/category','CategoryController');
Route::apiResource('/question/{question}/reply','ReplyController');
Route::get('/{question}/tag','TagController@index');
Route::patch('/{question}/tag','TagController@edit');
Route::get('/tag','TagController@TagToQuestion');

Route::get('/user','UserController@getEntireData');
Route::post('/user','UserController@getRecent');




Route::post('/like/{reply}','LikeController@likeIt');
Route::delete('/like/{reply}','LikeController@unLikeIt');

Route::post("notifications",'NotificationController@index');
Route::post("markAsRead",'NotificationController@markAsRead');



Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
