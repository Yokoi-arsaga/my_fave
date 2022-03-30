<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * repositoryクラスの依存関係を定義
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * repositoryと紐づくModel名を追記することでbindされます
     *
     * @var array
     */
    private const MODELS = [
        'Thumbnail',
        'User',
        'SnsAccount',
        'FriendRequest',
        'Friend',
        'FavoriteVideo',
        'ParentFolder'
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (self::MODELS as $model) {
            $this->app->bind(
                "App\Repositories\\{$model}\\{$model}RepositoryInterface",
                "App\Repositories\\{$model}\\{$model}Repository"
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
