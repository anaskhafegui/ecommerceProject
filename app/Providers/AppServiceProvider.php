<?php

namespace App\Providers;

use App\Cart\Cart;
use App\Cart\Wishlist;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
            $app->auth->user()->load([
                'cart.stock'
            ]);

            return new Cart($app->auth->user());
        });


        $this->app->singleton(Wishlist::class, function ($app) {

            return new Wishlist($app->auth->user());
        });



    }
}
