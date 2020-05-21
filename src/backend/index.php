<?php
/**
 * Dijkstra backend
 *
 * PHP version 7.2
 *
 * Ratkaisun backend-rajapinta
 *
 * @category  Backend
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */

 namespace KOODIHAASTE;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$startup = getenv("koodihaaste") ? getenv("koodihaaste")."/src/startup.php" : false;
if (!$startup) {
    die("Environment not set properly, check .httaccess!");
}

require $startup;
require $conf->get("General")["vendor"];
$f3 = \Base::instance();
$f3->set("conf", $conf);
$f3->set("db", $db);
$f3->set("log", $log);

$cnodes = new NodeController();
$f3->map("/edges", '\KOODIHAASTE\EdgesController');
$f3->map("/nodes", '\KOODIHAASTE\NodeController');
$f3->map("/djikstra", '\KOODIHAASTE\DijkstraController');
$f3->route("GET /foo", function () {
    die("Routing works");
});
$f3->run();
