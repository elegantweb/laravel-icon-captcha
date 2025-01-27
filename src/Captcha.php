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

    public function getPlacement()
    {
        return $this->placement;
    }

    public function leastRepeatedIconType(): int
    {
        $positions = $this->placement->getPositions();

        $repeats = array_count_values($positions);

        return array_keys($repeats, min($repeats))[0];
    }

    public function validateCoordinates(int $x, int $y)
    {
        $position = $this->placement->findPositionByCoordinates($x, $y);
        if (null === $position) return false;

        $positions = $this->placement->getPositions();
        $iconType = $positions[$position];

        return $this->leastRepeatedIconType() === $iconType;
    }

    public function render(): string
    {
        return $this->board->render();
    }
}
