<?php
namespace KOODIHAASTE;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$startup = getenv("koodihaaste") ? getenv("koodihaaste")."/src/startup.php" : FALSE;
if(!$startup) {
    die("Environment not set properly, check .httaccess!");
}

require $startup;
require $conf->get("General")["vendor"];
$f3 = \Base::instance();
$f3->set("conf", $conf);
$f3->set("db", $db);
$f3->set("log", $log);

$cnodes = new cnode();
$f3->map("/edges",'\KOODIHAASTE\cedges');
$f3->map("/nodes", '\KOODIHAASTE\cnode');
$f3->map("/djikstra", '\KOODIHAASTE\cdjikstra');
$f3->route("GET /foo", function () {
    die("Routing works");
});
$f3->run();
