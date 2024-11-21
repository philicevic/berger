<?php

namespace Philicevic\Berger\Test\Models;

use Philicevic\Berger\Models\Fixture;
use Philicevic\Berger\Models\Round;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    protected Round $round;

    protected function setUp(): void
    {
        parent::setUp();
        $this->round = new Round(1);
    }

    public function testConstructor(): void
    {
        $this->assertEquals(1, $this->round->number);
    }

    public function testAddFixture(): void
    {
        $this->round->addFixture($this->getFixture());
        $this->assertCount(1, $this->round->fixtures);
    }

    public function testRemoveFixture(): void
    {
        $this->round->addFixture($this->getFixture());
        $this->assertCount(1, $this->round->fixtures);
        $this->round->removeFixture(0);
        $this->assertCount(0, $this->round->fixtures);
    }

    public function testToArray(): void
    {
        $this->round->addFixture($this->getFixture());
        $array = $this->round->toArray();
        $this->assertIsArray($array);
        foreach ($array as $fixture) {
            $this->assertArrayHasKey('home', $fixture);
            $this->assertArrayHasKey('away', $fixture);
            $this->assertArrayHasKey('round', $fixture);
            $this->assertEquals([
                'home' => 'A',
                'away' => 'B',
                'round' => 1
            ], $fixture);
        }
    }

    protected function getFixture(): Fixture
    {
        return new Fixture('A', 'B');
    }
}