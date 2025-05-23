<?php

namespace App\Http\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\Contracts\AuthRepositoryInterface;
use App\Http\Repositories\Eloquent\AuthRepositories;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepositories::class);
    }
}