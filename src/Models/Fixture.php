<?php

namespace Philicevic\Berger\Models;

class Fixture
{
    public string $home = '';
    public string $away = '';

    public function __construct(string $home = '', string $away = '')
    {
        $this->home = $home;
        $this->away = $away;
    }

    public function __toString(): string
    {
        return $this->home . ' - ' . $this->away;
    }
}