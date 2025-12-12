<?php

namespace App\Providers;

use App\Repositories\BannerRepository;
use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 绑定接口的实现类
        $this->app->bind(
            BannerRepositoryInterface::class,
            BannerRepository::class  // 使用这个具体类
        );
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
