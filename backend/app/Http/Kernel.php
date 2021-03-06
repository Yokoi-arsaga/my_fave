<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Fruitcake\Cors\HandleCors::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'thumbnail.have' => \App\Http\Middleware\HaveThumbnail::class,
        'friend.request' => \App\Http\Middleware\FriendRequestStore::class,
        'friend.request.permission'  => \App\Http\Middleware\FriendRequestPermission::class,
        'child.folder.store' => \App\Http\Middleware\ChildFolderStore::class,
        'grandchild.folder.store' => \App\Http\Middleware\GrandchildFolderStore::class,
        'favorite.register' => \App\Http\Middleware\FavoriteVideoRegister::class,
        'multi.favorite.register' => \App\Http\Middleware\MultiFavoriteVideoRegister::class,
        'register.to.parent' => \App\Http\Middleware\RegisterToParentFolder::class,
        'register.to.child' => \App\Http\Middleware\RegisterToChildFolder::class,
        'register.to.grandchild' => \App\Http\Middleware\RegisterToGrandchildFolder::class,
        'multi.register.to.parent' => \App\Http\Middleware\MultiRegisterToParentFolder::class,
        'multi.register.to.child' => \App\Http\Middleware\MultiRegisterToChildFolder::class,
        'multi.register.to.grandchild' => \App\Http\Middleware\MultiRegisterToGrandchildFolder::class,
        'change.registration.to.parent' => \App\Http\Middleware\ChangeRegistrationToParentFolder::class,
        'change.registration.to.child' => \App\Http\Middleware\ChangeRegistrationToChildFolder::class,
        'change.registration.to.grandchild' => \App\Http\Middleware\ChangeRegistrationToGrandchildFolder::class,
    ];
}
