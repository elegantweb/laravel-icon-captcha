<?php

use Elegant\Captcha\Icon\CaptchaPlacement;
use Elegant\Captcha\Icon\CaptchaValidator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

if (!function_exists('captcha_icon_check')) {
    function captcha_icon_check(int $x, int $y): bool
    {
        /** @var CaptchaPlacement */
        $captchaPlacement = Session::get('captcha.icon');
        $captchaValidator = new CaptchaValidator($captchaPlacement);

        return $captchaValidator->validateCoordinates($x, $y);
    }
}

if (!function_exists('captcha_icon_src')) {
    function captcha_icon_src(array $params = []): string
    {
        return URL::route('captcha.icon.image', $params);
    }
}
