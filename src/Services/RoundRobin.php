<?php

namespace Philicevic\Berger\Services;

use Philicevic\Berger\Models\Fixture;
use Philicevic\Berger\Models\Round;

class RoundRobin {
    private array $teams;

    private array $rounds = [];

    public function __construct(array $teams = [])
    {
        $this->teams = $teams;
    }

    public static function create(array $teams = []): self
    {
        return new self($teams);
    }

    /**
     * @return array<Round>
     */
    public static function makeFromTeams(array $teams): array
    {
        return self::create($teams)->make();
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

        return $this->rounds;
    }
}