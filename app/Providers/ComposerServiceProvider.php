<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with([
                'categories' => Category::where('type', 'category')->get()
            ]);
        });

        view()->composer('product.create', function ($view) {
            $view->with([
                'brands'     => Brand::all()
            ]);
        });

        view()->composer('category.index', function ($view) {
            $view->with([
                'defaultSearch' => json_encode([
                    'name' => "",
                    'brands' => Brand::all(),
                    'categories' => Category::where('type', 'category')->get(),
                    'price' => [0,1000]
                ])
            ]);
        });
    }
}
