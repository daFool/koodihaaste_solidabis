<?php
/**
 * Frontend
 *
 * PHP version 7.2
 *
 * Käyttöliittymä
 *
 * @category  Frontend
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

$f3->route("GET /", function ($f3) {
    $conf = $f3->get("conf");
    $loader = new \Twig\Loader\FilesystemLoader($conf->get("Twig")["twigTemplates"]);
    $twig = new \Twig\Environment($loader);
    $basepath = $conf->get("General")["basePath"];
    require "$basepath/tekstit.php";
    $sivu = new PageView($twig, $t, $conf);
    $sivu->nayta("etusivu.html");
});

$f3->run();
