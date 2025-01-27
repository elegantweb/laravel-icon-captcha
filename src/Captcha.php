<?php

namespace Elegant\Captcha\Icon;

class Captcha
{
    protected CaptchaBoard $board;
    protected CaptchaPlacement $placement;

    public function __construct(CaptchaBoard $board, CaptchaPlacement $placement)
    {
        $this->board = $board;
        $this->placement = $placement;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function getPlacement()
    {
        return $this->placement;
    }

    public function render(): string
    {
        return $this->board->render();
    }
}
