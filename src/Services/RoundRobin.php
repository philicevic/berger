<?php

namespace Philicevic\Berger\Services;

use Philicevic\Berger\Models\Fixture;
use Philicevic\Berger\Models\Round;

class RoundRobin {
    private array $teams;

    private array $rounds = [];

    private int $matchesAgainstEachOther;

    public function __construct(array $teams = [], int $matchesAgainstEachOther = 1)
    {
        if ($matchesAgainstEachOther < 1) {
            throw new \Exception("Value matchesAgainstEachOther is not allowed to be less than 1.");
        }
        $this->teams = $teams;
        $this->matchesAgainstEachOther = $matchesAgainstEachOther;
    }

    public static function create(array $teams = [], int $matchesAgainstEachOther = 1): self
    {
        return new self($teams, $matchesAgainstEachOther);
    }

    /**
     * @return array<Round>
     */
    public static function makeFromTeams(array $teams, int $matchesAgainstEachOther = 1): array
    {
        return self::create($teams, $matchesAgainstEachOther)->make();
    }

    public function setTeams(array $teams): void
    {
        $this->teams = $teams;
    }

    /**
     * @return array<Round>
     */
    public function make(): array
    {
        $teamCount = count($this->teams);
        $rounds = $teamCount % 2 ? $teamCount : $teamCount - 1;
        $matchesPerRound = $teamCount % 2 ? ($teamCount-1)/2 : $teamCount/2;
        $fixedTeam = array_pop($this->teams);

        // If teamCount is odd, add dummy team to allow proper rotation
        if ($teamCount % 2) {
            $this->teams[] = false;
        }
        // In addition to the teamCount we need the count of the actual rotation
        $rotationCount = count($this->teams);

        $round = 1;
        while ($round <= $rounds) {
            $match = 0;
            $this->rounds[$round] = new Round($round);
            while (count($this->rounds[$round]->fixtures) < $matchesPerRound) {
                // Only add fixture if both teams are real
                if ($this->teams[$match] && $this->teams[$rotationCount - $match - 1]) {
                    // If team plays itself, let it play the fixed team instead
                    if ($this->teams[$match] == $this->teams[$rotationCount - $match - 1]) {
                        // alter home and away games for the fixed team
                        $fixture = $round % 2 ? new Fixture($this->teams[$match], $fixedTeam) : new Fixture($fixedTeam, $this->teams[$match]);

                    }
                    else {
                        $fixture = new Fixture($this->teams[$match], $this->teams[$rotationCount - $match - 1]);
                    }
                    $this->rounds[$round]->addFixture($fixture);
                }

                $match++;
            }
            // Rotate team list
            $shiftedTeam  = array_pop($this->teams);
            array_unshift($this->teams, $shiftedTeam);

            $round++;
        }

        $this->rounds = $this->multiply($this->rounds, $this->matchesAgainstEachOther);

        return $this->rounds;
    }

    /**
     * @param array<Round> $rounds
     * @param int $matchesAgainstEachOther
     * @return array
     */
    private function multiply(array $rounds, int $matchesAgainstEachOther): array
    {
        $baseRounds = $rounds;

        for ($i = 1; $i < $matchesAgainstEachOther; $i++) {
            $newRounds = $baseRounds;
            foreach ($newRounds as $round) {
                $rounds[] = $i % 2 ? $round->createReverse(count($rounds) + 1) : $round->duplicate(count($rounds) + 1);
            }
        }

        return $rounds;
    }
}