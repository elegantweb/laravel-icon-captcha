<?php

namespace Elegant\Captcha\Icon;

class CaptchaValidator
{
    protected CaptchaPlacement $placement;

    public function __construct(CaptchaPlacement $placement)
    {
        $this->placement = $placement;
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
}
