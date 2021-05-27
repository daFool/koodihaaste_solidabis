<?php
namespace KOODIHAASTE;

/**
 * Startti
 *
 * PHP version 7.2
 *
 * @category  Common
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 *
 * Tarkistaa ympäristömuuttujat:
 * mosBase - polku mosBase-projektiin
 * koodihaasteIni - konfiguraatiotiedosto
 * koodihaaste - Koodihaaste-projektin juurihakemisto
 * Lukee konfiguraation, avaa kantayhteyden ja luo login.h-25
 *
 */
$mosBase = getenv("mosBase");
$inifile = getenv("koodihaasteIni");
$base = getenv("koodihaaste");

if (!$mosBase) {
    die("mosBase environment variable not set.");
}
if (!$inifile) {
    die("koodihaasteIni environment variable not set.");
}
if (!$base) {
    die("koodihaaste environment variable not set.");
}

require "$mosBase/util/Config.php";

try {
    $conf = new \mosBase\Config();
    $conf->init($inifile);
    $dbconf = $conf->get("Database");
} catch (\mosBase\ConfigException $ce) {
    die($ce->getMessage());
}
try {
    $db = new \mosBase\Database($dbconf["dsn"], $dbconf["user"], $dbconf["password"]);
    $log = new \mosBase\Log("AUDIT", $db);
} catch (\PDOException $pe) {
    die($pe->getMessage());
}