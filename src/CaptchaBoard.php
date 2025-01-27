<?php

namespace Elegant\Captcha\Icon;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;
use OutOfRangeException;

class CaptchaBoard
{
    protected ImagineInterface $imagine;
    protected $image;

    protected int $spaceBorder;
    protected int $spaceBetween;

    protected int $iconCount;
    protected Box $iconSize;

    protected ColorInterface $mainColor;
    protected ColorInterface $bgColor;

    public function __construct(
        int $iconSize,
        int $iconCount,
        int $spaceBorder = 16,
        int $spaceBetween = 16,
        string $mainColor = '#000',
        string $bgColor = '#fff',
    ) {
        $this->iconSize = new Box($iconSize, $iconSize);
        $this->iconCount = $iconCount;

        $this->spaceBorder = $spaceBorder;
        $this->spaceBetween = $spaceBetween;

        $this->mainColor = (new RGB)->color($mainColor);
        $this->bgColor = (new RGB)->color($bgColor);

        // TODO: use factory pattern and create Imagine object based on available drivers
        $this->imagine = new Imagine();
        $this->image = $this->imagine->create($this->calcBoardSize());
    }

    protected function calcBoardSize(): BoxInterface
    {
        return new Box(
            ($this->iconSize->getWidth() * $this->iconCount) + ($this->spaceBetween * ($this->iconCount - 1)) + ($this->spaceBorder * 2),
            $this->iconSize->getHeight() + ($this->spaceBorder * 2),
        );
    }

    protected function resolveIconPosition(int $i, ImageInterface $icon): PointInterface
    {
        $iconSize = $this->iconSize;

        $diffWidth = ($icon->getSize()->getWidth() - $iconSize->getWidth()) / 2;
        $diffHeight = ($icon->getSize()->getHeight() - $iconSize->getHeight()) / 2;

        return new Point(
            ($iconSize->getWidth() * $i) + ($this->spaceBetween * $i) + $this->spaceBorder - $diffWidth,
            $this->spaceBorder - $diffHeight
        );
    }

    public function addRandomScratch(): void
    {
        $imageWidth = $this->image->getSize()->getWidth();
        $imageHeight = $this->image->getSize()->getHeight();

        $this->image->draw()->line(
            new Point(rand(0, $imageWidth), rand(0, $imageHeight)),
            new Point(rand(0, $imageWidth), rand(0, $imageHeight)),
            $this->mainColor,
        );
    }

    public function addText(string $text, bool $bottom = false): void
    {
        $imgWidth = $this->image->getSize()->getWidth();
        $imgHeight = $this->image->getSize()->getHeight();

        $font = $this->imagine->font(__DIR__ . '/../font/SpaceMono-Regular.ttf', 14, $this->mainColor);

        $box = $font->box($text);
        $boxWidth = $box->getWidth();
        $boxHeight = $box->getHeight();

        if ($bottom) {
            $point = new Point(rand(0, $imgWidth - $boxWidth), rand($imgHeight / 2, $imgHeight - $boxHeight));
        } else {
            $point = new Point(rand(0, $imgWidth - $boxWidth), rand(0, $this->spaceBorder));
        }

        $this->image->draw()->text($text, $font, $point);
    }

    /**
     * @param int $i Icon index
     * @param int $path Icon file path
     * @return array Array of icon coordinates inside the image
     */
    public function addIconToImage(int $i, string $path): array
    {
        if (($i + 1) > $this->iconCount) {
            throw new OutOfRangeException("Max icon index is: " . $this->iconCount);
        }

        $icon = $this->imagine->open($path);
        $icon->resize($this->iconSize);
        $rotatedIcon = $icon->copy()->rotate($this->getRandomIconRotationDegree());

        $point = $this->resolveIconPosition($i, $rotatedIcon);
        $size = $rotatedIcon->getSize();

        $this->image->paste($rotatedIcon, $point);

        return [
            $point->getX(),
            $point->getX() + $size->getWidth(),
            $point->getY(),
            $point->getY() + $size->getHeight(),
        ];
    }

    protected function getRandomIconRotationDegree(): int
    {
        return [0, 45, 90, 135, 180, 225, 270, 315][random_int(0, 7)];
    }

    public function render(): string
    {
        return $this->image->get('png', []);
    }
}
