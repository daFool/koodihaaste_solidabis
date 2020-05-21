#!/usr/bin/php
<?php
/**
 * routeIt
 *
 * PHP version 7.2
 *
 * Pääohjelma komentorivi-ratkaisimelle.
 *
 * @category  Commandline
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
namespace KOODIHAASTE;

if ($argc != 3) {
    die("${argv[0]}: <from> <to>($argc)".PHP_EOL);
}
 
require "startup.php";

$d = new DijkstraModel($db, $log);
$res = $d->route($argv[1], $argv[2]);
if ($res[0]===false || empty($res[1])) {
    die("No route from ${argv[1]} to ${argv[2]}".PHP_EOL);
}
$route = $d->processResult($res);
foreach ($route as $step) {
    printf(
        "%s->%s with %s for %d/%d %s",
        $step[DijkstraModel::FROM],
        $step[DijkstraModel::TO],
        implode(", ", $step[DijkstraModel::WITH]),
        $step[DijkstraModel::FOR],
        $step[DijkstraModel::TRAVELED],
        PHP_EOL
    );
}
