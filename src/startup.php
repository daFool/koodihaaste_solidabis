<?php
namespace KOODIHAASTE;

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
}
catch(\mosBase\ConfigException $ce) {
    die($ce->getMessage());
}    
try {
    $db = new \mosBase\Database($dbconf["dsn"], $dbconf["user"], $dbconf["password"]);
    $log = new \mosBase\Log("AUDIT", $db);
} 
catch(\PDOException $pe) {
    die($pe->getMessage());
}
