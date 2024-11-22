<?php

namespace Philicevic\Berger\Models;

class Round
{
    /**
     * @var array<Fixture>
     */
    public array $fixtures = [];
    public int $number = 0;

    public function __construct(int $number = 0)
    {
        $this->number = $number;
    }

    public function addFixture(Fixture $fixture): void
    {
        $this->fixtures[] = $fixture;
    }

    public function removeFixture(int $index): void
    {
        array_splice($this->fixtures, $index, 1);
    }

    public function duplicate(int $number): self
    {
        $clone = clone $this;
        $clone->number = $number;
        return $clone;
    }

    public function createReverse(int $number): self
    {
        return $this->duplicate($number)->swapSides();
    }

    public function swapSides(): self
    {
        for ($i = 0; $i < count($this->fixtures); $i++) {
            $this->fixtures[$i] = $this->fixtures[$i]->rematch();
        }

        return $this;
    }

    public function toArray(): array
    {
        return array_map(fn ($fixture) => [
            'home' => $fixture->home,
            'away' => $fixture->away,
            'round' => $this->number,
        ], $this->fixtures);
    }
}