<?php

namespace Elegant\Captcha\Icon;

use Generator;
use UnexpectedValueException;
use Elegant\Captcha\Icon\Contracts\CaptchaBuilder as CaptchaBuilderContract;

class CaptchaBuilder implements CaptchaBuilderContract
{
    /**
     * @var int Total number of icons to draw
     */
    protected int $iconCount = 6;

    /**
     * @var int Icon size in pixels
     */
    protected int $iconSize = 48;

    /**
     * @var int How many icons types to draw
     */
    protected int $iconTypes = 2;

    /**
     * @var string Path to the directory of icons
     */
    protected string $iconPack;

    /**
     * @var int Whitespace between border of image and icons in pixels
     */
    protected int $spaceBorder = 16;

    /**
     * @var int Whitespace between icons in pixels
     */
    protected int $spaceBetween = 16;

    /**
     * @var string Font and all drawing color in hex format
     */
    protected string $mainColor = '#000';

    /**
     * @var string Board background color in hex format
     */
    protected string $bgColor = '#fff';

    /**
     * @var int
     */
    protected int $scratchCount = 8;

    /**
     * @var string[] Texts to add to top half of captcha image
     */
    protected array $textTop = [];

    /**
     * @var string[] Texts to add to bottom half of captcha image
     */
    protected array $textBottom = [];

    public function setIconPack(string $path): static
    {
        $this->iconPack = $path;

        return $this;
    }

    public function setIconCount(int $count): static
    {
        $this->iconCount = $count;

        return $this;
    }

    public function setIconSize(int $size): static
    {
        $this->iconSize = $size;

        return $this;
    }

    public function setIconTypes(int $types): static
    {
        $this->iconTypes = $types;

        return $this;
    }

    public function setSpaceBorder(int $size): static
    {
        $this->spaceBorder = $size;

        return $this;
    }

    public function setSpaceBetween(int $size): static
    {
        $this->spaceBetween = $size;

        return $this;
    }

    public function setMainColor(string $color): static
    {
        $this->mainColor = $color;

        return $this;
    }

    public function setBgColor(string $color): static
    {
        $this->bgColor = $color;

        return $this;
    }

    public function setScratchCount(int $count): static
    {
        $this->scratchCount = $count;

        return $this;
    }

    public function addText(string $text, bool $bottom = false): static
    {
        if ($bottom) $this->textBottom[] = $text;
        else $this->textTop[] = $text;

        return $this;
    }

    protected function readIconFilenames(): Generator
    {
        $handle = opendir($this->iconPack);
        if (false === $handle) {
            throw new UnexpectedValueException('Failed to open directory to read: ' . $this->iconPack);
        }

        while (false !== ($entry = readdir($handle))) {
            if ('.' !== $entry && '..' !== $entry) {
                yield $this->iconPack . DIRECTORY_SEPARATOR . $entry;
            }
        }
    }

    /**
     * Randomly determine how many times each icon type should be repeated.
     *
     * @param int $types Total icon types
     * @param int $total Total icons
     */
    protected function randomIconCounts(int $types, int $total)
    {
        $counts = [];

        for ($i = 1; $i <= $types; $i++) {
            // first number, save room for all remaining types
            if ($i === 1) $counts[] = random_int(1, $total - $types);
            // last number, get all remaining positions
            elseif ($i === $types) $counts[] = $total - array_sum($counts);
            else $counts[] = random_int(1, $total - array_sum($counts) - ($types - $i));
        }

        // if the smallest number is not unique, repeat
        $duplicates = array_diff_key($counts, array_unique($counts));
        if (in_array(min($counts), $duplicates)) {
            return $this->randomIconCounts($types, $total);
        }

        return $counts;
    }

    protected function randomIconName(array $filenames, array $except = [])
    {
        $name = $filenames[random_int(0, count($filenames) - 1)];

        if (in_array($name, $except)) {
            return $this->randomIconName($filenames, $except);
        } else {
            return $name;
        }
    }

    protected function randomIconNames(array $filenames)
    {
        $names = [];

        for ($i = 0; $i < $this->iconTypes; $i++) {
            $names[] = $this->randomIconName($filenames, $names);
        }

        return $names;
    }

    public function build(): Captcha
    {
        $iconFilenames = iterator_to_array($this->readIconFilenames());

        $iconNames = $this->randomIconNames($iconFilenames);
        $iconCounts = $this->randomIconCounts($this->iconTypes, $this->iconCount);

        $placement = CaptchaPlacement::createRandom($iconCounts);

        $board = new CaptchaBoard(
            $this->iconSize,
            $this->iconCount,
            $this->spaceBorder,
            $this->spaceBetween,
            $this->mainColor,
            $this->bgColor,
        );

        foreach ($placement->getPositions() as $position => $iconType) {
            $c = $board->addIconToImage($position, $iconNames[$iconType]);
            $placement->addLocation($position, $c);
        }

        for ($i = 0; $i < $this->scratchCount; $i++) {
            $board->addRandomScratch();
        }

        foreach ($this->textTop as $text) {
            $board->addText($text);
        }

        foreach ($this->textBottom as $text) {
            $board->addText($text, true);
        }

        return new Captcha($board, $placement);
    }
}
