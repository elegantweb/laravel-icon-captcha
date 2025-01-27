<?php

use Elegant\Captcha\Icon\Captcha;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

if (!function_exists('captcha_icon_check')) {
    function captcha_icon_check(int $x, int $y): bool
    {
        /** @var Captcha */
        $captcha = Session::get('captcha.icon');

        return $captcha->validateCoordinates($x, $y);
    }
}

if (!function_exists('captcha_icon_src')) {
    function captcha_icon_src(array $params = []): string
    {
        return URL::route('captcha.clock.image', $params);
    }
}
