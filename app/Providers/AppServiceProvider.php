<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('activeClass', function ($routes) {
            return <<<PHP
<?php
    echo request()->routeIs($routes)
        ? 'relative text-[#efb034] font-semibold
           after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full
           after:bg-[#efb034] after:rounded-full'
        : 'text-neutral-700 dark:text-neutral-300
           hover:text-neutral-900 dark:hover:text-white';
?>
PHP;
        });

        Blade::directive('activeClassSide', function ($condition) {
            return <<<PHP
<?php
    echo ($condition)
        ? 'relative
           bg-indigo-50 text-indigo-700
           dark:bg-indigo-900/40 dark:text-indigo-300
           before:absolute before:left-0 before:top-2 before:bottom-2
           before:w-1 before:rounded-r before:bg-indigo-600'
        : 'text-gray-600
           dark:text-gray-400
           hover:bg-gray-100 hover:text-gray-900
           dark:hover:bg-gray-800 dark:hover:text-white';
?>
PHP;
        });



    }
}
