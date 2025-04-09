<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('test', function(){
    return "Hello World";
});

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
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
