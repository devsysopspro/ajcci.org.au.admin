<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Add debug route to check if API is accessible
Route::get('debug', function() {
    return response()->json(['status' => 'API is working']);
});

Route::get('test', function(){
    return "Hello World";
});

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {
    // Add logging middleware
    Route::middleware(['log.route'])->group(function () use ($router) {
        Route::match(['GET', 'POST', 'OPTIONS'], 'login', function() {
            $request = request();
            Log::info('Route hit: ' . $request->method());
            if ($request->isMethod('OPTIONS')) {
                return response()->json([], 200);
            }
            $response = app(AuthController::class)->login($request);
            return $response->setStatusCode(200); // Force status code to 200
        });
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
        Route::post('changePassword', [AuthController::class, 'changePassword']);
    });
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
