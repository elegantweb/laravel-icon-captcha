<?php

namespace Elegant\Captcha\Icon;

use Elegant\Captcha\Icon\Contracts\CaptchaBuilder as CaptchaBuilderContract;
use Elegant\Captcha\Icon\Contracts\CaptchaDirector as CaptchaDirectorContract;
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
        $validator->extend('captcha_icon', function ($attribute, $value, $parameters) {
            return is_array($value)
                   && isset($value[0], $value[1])
                   && captcha_icon_check((int) $value[0], (int) $value[1]);
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

        $this->app->bind(CaptchaBuilderContract::class, CaptchaBuilder::class);
        $this->app->singleton(CaptchaDirectorContract::class, CaptchaDirector::class);
    }
}
