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

$f3->route("GET /", function($f3) {
    $conf = $f3->get("conf");
    $loader = new \Twig\Loader\FilesystemLoader($conf->get("Twig")["twigTemplates"]);
    $twig = new \Twig\Environment($loader);
    $basepath = $conf->get("General")["basePath"];
    require "$basepath/tekstit.php";
    $sivu = new vPage($twig, $t, $conf);
    $sivu->nayta("etusivu.html");
});

$f3->run();
