<?php

namespace Elegant\Captcha\Icon\Contracts;

use Elegant\Captcha\Icon\Captcha;

interface CaptchaBuilder
{
    public function setIconPack(string $path): static;

    public function setIconCount(int $count): static;

    public function setIconSize(int $size): static;

    public function setIconTypes(int $types): static;

    public function setSpaceBorder(int $size): static;

    public function setSpaceBetween(int $size): static;

    public function setMainColor(string $color): static;

    public function setBgColor(string $color): static;

    public function setScratchCount(int $count): static;

    public function addText(string $text, bool $bottom = false): static;

    public function build(): Captcha;
}
