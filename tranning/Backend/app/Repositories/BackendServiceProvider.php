<?php

namespace Backend\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Backend\Repositories\ReleaseNumberRepositoryInterface',
            'Backend\Repositories\ReleaseNumberRepository'
        );
        $this->app->bind(
            'Backend\Repositories\ImageRepositoryInterface',
            'Backend\Repositories\ImageRepository'
        );
        $this->app->bind(
            'Backend\Repositories\CategoryRepositoryInterface',
            'Backend\Repositories\CategoryRepository'
        );
        $this->app->bind(
            'Backend\Repositories\PostRepositoryInterface',
            'Backend\Repositories\PostRepository'
        );
        $this->app->bind(
            'Backend\Repositories\UserAdminRepositoryInterface',
            'Backend\Repositories\UserAdminRepository'
        );
        $this->app->bind(
            'Backend\Repositories\MemberRepositoryInterface',
            'Backend\Repositories\MemberRepository'
        );
        $this->app->bind(
            'Backend\Repositories\SettingRepositoryInterface',
            'Backend\Repositories\SettingRepository'
        );

    }
}
