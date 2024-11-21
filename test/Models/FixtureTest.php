<?php

namespace Philicevic\Berger\Test\Models;

use Philicevic\Berger\Models\Fixture;
use PHPUnit\Framework\TestCase;

class FixtureTest extends TestCase
{
    protected Fixture $fixture;
    protected array $teams;
    protected function setUp(): void
    {
        parent::setUp();
        $this->teams = ['A', 'B'];
        $this->fixture = new Fixture(...$this->teams);
    }

    public function testConstructor(): void
    {
        self::assertEquals($this->teams[0], $this->fixture->home);
        self::assertEquals($this->teams[1], $this->fixture->away);
    }
}