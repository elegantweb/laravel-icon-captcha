<?php

namespace Elegant\Captcha\Icon;

use Elegant\Captcha\Icon\Contracts\CaptchaBuilder;
use Elegant\Captcha\Icon\Contracts\CaptchaDirector as CaptchaDirectorContract;

class CaptchaDirector implements CaptchaDirectorContract
{
    public function build(CaptchaBuilder $builder): Captcha
    {
        return $builder->build();
    }
}
