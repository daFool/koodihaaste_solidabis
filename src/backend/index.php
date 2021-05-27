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

$startup = getenv("koodihaaste");
if (!$startup) {
    die("Environment not set properly, check .httaccess!");
}

require $startup."/src/webstartup.php";

$cnodes = new NodeController();
$f3->map("/edges", '\KOODIHAASTE\EdgesController');
$f3->map("/nodes", '\KOODIHAASTE\NodeController');
$f3->map("/djikstra", '\KOODIHAASTE\DijkstraController');
$f3->route("GET /foo", function () {
    die("Routing works");
});
$f3->run();
