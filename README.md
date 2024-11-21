# Berger Tables for PHP

This package allows you to generate round robin tournament matchups based on the [Berger tables](https://en.wikipedia.org/wiki/Round-robin_tournament#Berger_tables).

## Usage

```
use Philicevic\Berger\Services\RoundRobin;


$teams = ['A', 'B', 'C', 'D'];
$rounds = RoundRobin::makeFromTeams($teams);

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
    
    // Will return something like this
    [
        
    ]
}
```