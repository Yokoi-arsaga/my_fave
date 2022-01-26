<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * repositoryと紐づくModel名を追記することでbindされます
     *
     * @var array
     */
    private const MODELS = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (self::MODELS as $model) {
            $this->app->bind(
                "App\Repositories\Interfaces\\{$model}RepositoryInterface",
                "App\Repositories\Eloquent{$model}Repository"
            );
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
