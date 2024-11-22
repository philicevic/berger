# Berger Tables for PHP

This package allows you to generate round robin tournament matchups based on the [Berger tables](https://en.wikipedia.org/wiki/Round-robin_tournament#Berger_tables).

## Usage

```
use Philicevic\Berger\Services\RoundRobin;

// this creates 6 rounds where teams play each other twice
$teams = ['A', 'B', 'C', 'D'];
$matchesAgainstEachOther = 2;
$rounds = RoundRobin::makeFromTeams($teams, $matchesAgainstEachOther);


// or use it step by step

$teams = ['A', 'B', 'C', 'D'];
$rr = RoundRobin::create();
$rr->setTeams($teams);
$rounds = $rr->make();
```
`$rounds` will be an array of `Round` objects.

### Round

```
foreach ($rounds as $round) {
    // get array of fixtures
    $round->fixtures;
    
    // get number of round
    $round->number;
    
    // add fixture to round
    $round->addFixture($fixture);
    
    // remove fixture from round by index
    $round->removeFixture($index);
    
    // format round as array
    $round->toArray();
}

// $round->toArray() will return something like this
[
    [
        [
            'home' => 'A',
            'away' => 'C',
            'round' => 1,
        ],
        [
            'home' => 'B',
            'away' => 'D',
            'round' => 1,
        ],
    ],
    [
        [
            'home' => 'C',
            'away' => 'B',
            'round' => 2,
        ],
        [
            'home' => 'D',
            'away' => 'A',
            'round' => 2,
        ],
    ],
    [
        [
            'home' => 'A',
            'away' => 'B',
            'round' => 3,
        ],
        [
            'home' => 'C',
            'away' => 'D',
            'round' => 3,
        ],
    ],
]
```

### Fixture
Every fixture has two properties.

```
$fixture->home
$fixture->away
```
Both contain just the name of the team.