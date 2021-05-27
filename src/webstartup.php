<?php
/**
 * Dijkstra web startup
 *
 * PHP version 7.2
 *
 * Ratkaisun web sartup
 *
 * @category  Web
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */

require getenv("koodihaaste")."/src/startup.php";

require $conf->get("General")["vendor"];
$f3 = \Base::instance();
$f3->set("conf", $conf);
$f3->set("db", $db);
$f3->set("log", $log);
