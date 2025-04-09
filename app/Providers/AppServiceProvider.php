<?php

namespace App\Providers;

use App\Component\ContentEloquentToContentDtoConverter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(ContentEloquentToContentDtoConverter::class, function ($app) {
            return new ContentEloquentToContentDtoConverter();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
