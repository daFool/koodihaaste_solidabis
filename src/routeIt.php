#!/usr/bin/php
<?php
namespace KOODIHAASTE;

if ($argc != 3) {
    die("${argv[0]}: <from> <to>($argc)".PHP_EOL);
}

require "startup.php";

$d = new djikstra($db, $log);
$res = $d->route($argv[1], $argv[2]);
if($res[0]===FALSE) {
    die("No route from ${argv[1]} to ${argv[2]}".PHP_EOL);
}
$route = $d->processResult($res);
foreach($route as $step) {
    printf("%s->%s with %s for %d/%d %s", 
        $step[djikstra::FROM],
        $step[djikstra::TO],
        serialize($step[djikstra::WITH]),
        $step[djikstra::FOR],
        $step[djikstra::TRAVELED],
        PHP_EOL);
}

