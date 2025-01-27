<?php

namespace Elegant\Captcha\Icon\Http\Controllers;

use Elegant\Captcha\Icon\CaptchaBuilder;
use Elegant\Captcha\Icon\CaptchaDirector;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class CaptchaController extends Controller
{
    public function image(
        Request $request,
        CaptchaBuilder $builder,
        CaptchaDirector $director,
    ) {
        $builder->setIconPack(Config::get('captcha.icon.icon_pack'));
        $builder->setIconCount(Config::get('captcha.icon.icon_count'));
        $builder->setIconSize(Config::get('captcha.icon.icon_size'));
        $builder->setIconTypes(Config::get('captcha.icon.icon_types'));
        $builder->setSpaceBorder(Config::get('captcha.icon.space_border'));
        $builder->setSpaceBetween(Config::get('captcha.icon.space_between'));
        $builder->setMainColor(Config::get('captcha.icon.main_color'));
        $builder->setBgColor(Config::get('captcha.icon.bg_color'));
        $builder->setScratchCount(Config::get('captcha.icon.scratch_count'));
        $captcha = $director->build($builder);

        $request->session()->put('captcha.icon', $captcha);

        $content = $captcha->render();

        return new Response($content, 200, ['Content-Type' => 'image/png']);
    }
}
