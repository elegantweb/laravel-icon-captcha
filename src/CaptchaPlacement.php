<?php

namespace Elegant\Captcha\Icon;

class CaptchaPlacement
{
    /**
     * @var array<int, int> Array<Position Index, Icon Type>
     */
    protected array $positions;

    /**
     * @var array<int, int[]> Array<Position Index, Icon Location>
     */
    protected array $locations;

    public function __construct(array $positions)
    {
        $this->positions = $positions;
    }

    /**
     * @return array<int, int> Array<Position, Icon Type>
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    public function addLocation(int $position, array $location): static
    {
        $this->locations[$position] = $location;

        return $this;
    }

    public function findPositionByCoordinates(int $x, int $y): ?int
    {
        foreach ($this->locations as $position => $location) {
            if ($location[0] < $x && $location[1] > $x && $location[2] < $y && $location[3] > $y) {
                return $position;
            }
        }

        return null;
    }

    protected static function randomPlace(array $positions, int $totalCount): int
    {
        $allPositions = range(0, ($totalCount - 1));
        $takenPositions = array_keys($positions);
        $remainingPositions = array_diff($allPositions, $takenPositions);
        $position = $remainingPositions[array_rand($remainingPositions)];

        return $position;
    }

    public static function createRandom(array $iconCounts): static
    {
        $totalCount = array_sum($iconCounts);

        $positions = [];

        foreach ($iconCounts as $iconType => $iconCount) {
            for ($i = 0; $i < $iconCount; $i++) {
                $positions[static::randomPlace($positions, $totalCount)] = $iconType;
            }
        }

        ksort($positions);

        return new static($positions);
    }
}
