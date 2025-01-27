<?php

namespace Elegant\Captcha\Icon\Contracts;

use Elegant\Captcha\Icon\Captcha;

interface CaptchaDirector
{
    public function build(CaptchaBuilder $builder): Captcha;
}
