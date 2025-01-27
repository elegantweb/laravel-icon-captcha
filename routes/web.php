<?php

use Elegant\Captcha\Icon\Http\Controllers\CaptchaController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => Config::get('captcha.icon.routes.middleware')], function () {
    Route::get('/captcha/icon/image', [CaptchaController::class, 'image'])
        ->name('captcha.icon.image');
});
