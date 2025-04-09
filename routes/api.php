<?php
Route::get('test', function(){
    return "Hello World";
});

Route::group(['prefix' => 'auth'], function ($router) {
    Route::any('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('changePassword', 'AuthController@changePassword');
});

Route::get('content/{slug}/formatted', 'Api\ContentController@bySlug');

Route::group([
    'middleware' => 'jwt.auth',
    'namespace' => 'Api'
], function ($router) {
    Route::get('meta-content/all', 'MetaContentController@all');
    Route::resource('meta-content', 'MetaContentController');
    Route::resource('content', 'ContentController');
//    Route::get('entry/{model_id}/get-by-model', 'EntryController@getByModel');

    Route::post('users/{id}/approve', 'UserController@approve');
    Route::post('users/{id}/delete', 'UserController@delete');
    Route::apiResource('users', 'UserController', ['except' => ['store', 'destroy']]);
});
