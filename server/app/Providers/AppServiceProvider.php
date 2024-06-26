<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Alaouy\Youtube\Facades\Youtube;

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
        // src : https://github.com/jenssegers/laravel-mongodb/issues/2233
        // Loader Alias
        $loader = AliasLoader::getInstance();
        Youtube::setApiKey('alskdfhwueoriwaksjdfnzxcvxzfserwesfasdfs');
        // SANCTUM CUSTOM PERSONAL-ACCESS-TOKEN
        $loader->alias(\Laravel\Sanctum\PersonalAccessToken::class, \App\Models\Sanctum\PersonalAccessToken::class);
    }
}
