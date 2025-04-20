<?php

namespace Philicevic\Berger\Test\Services;

use Philicevic\Berger\Models\Fixture;
use Philicevic\Berger\Services\RoundRobin;
use PHPUnit\Framework\TestCase;

class RoundRobinTest extends TestCase
{
    protected array $teams;
    protected function setUp(): void
    {
        parent::setUp();
        $this->teams = $this->getTeams();
    }

    public function test_creates_fixtures(): void
    {
        $rounds = RoundRobin::makeFromTeams($this->teams);
        $this->assertInstanceOf(Fixture::class, $rounds[1]->fixtures[0]);
        $this->assertCount(5, $rounds);
        $this->assertCount(3, $rounds[1]->fixtures);
    }

    public function test_equal_home_rights(): void
    {
        $rounds = RoundRobin::makeFromTeams($this->teams);

        $fullCounter = [];
        $firstHalfCounter = [];
        foreach ($this->teams as $team) {
            $fullCounter[$team] = 0;
            $firstHalfCounter[$team] = 0;
        }

        foreach ($rounds as $round) {
            foreach ($round->fixtures as $fixture) {
                $fullCounter[$fixture->home]++;
            }
        }

        $rounds = array_slice($rounds, 0, 3);

        foreach ($rounds as $round) {
            foreach ($round->fixtures as $fixture) {
                $firstHalfCounter[$fixture->home]++;
            }
        }

        foreach ($fullCounter as $count) {
            $this->assertThat(
                $count,
                $this->logicalOr(
                    $this->equalTo(2),
                    $this->equalTo(3)
                )
            );
        }

        foreach ($firstHalfCounter as $count) {
            $this->assertThat(
                $count,
                $this->logicalOr(
                    $this->equalTo(1),
                    $this->equalTo(2)
                )
            );
        }
    }

    public function test_multiple_matches_against_each_other(): void
    {
        $rounds = RoundRobin::makeFromTeams($this->teams, 2);
        $this->assertCount(10, $rounds);
    }

    protected function getTeams(): array
    {
        return [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
        ];
    }
}