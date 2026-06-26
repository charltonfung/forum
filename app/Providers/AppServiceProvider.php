<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 讓 $articles->links() 渲染出來的分頁連結使用 Tailwind class
        // 否則 Laravel 預設用 Bootstrap 的 page-link class，在本專案不會有樣式
        Paginator::useTailwind();
    }
}
