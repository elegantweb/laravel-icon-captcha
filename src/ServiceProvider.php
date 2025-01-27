<?php

namespace Elegant\Captcha\Icon;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $validator = $this->app['validator'];
        $validator->extend('captcha_icons', function ($attribute, $value, $parameters) {
            return isset($value[0], $value[1])
                    && captcha_icon_check($value[0], $value[1]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/captcha.icon.php', 'captcha.icon');
    }
}
