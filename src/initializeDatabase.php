#!/usr/bin/php
<?php
/**
 * Tietokannan alustus
 *
 * PHP version 7.2
 *
 * Lataa json-kartan tietokantaan.
 *
 * @category  Commandline
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
namespace KOODIHAASTE;

if ($argc != 2) {
    die("${argv[0]}: <tiedosto> ($argc)".PHP_EOL);
}

require "startup.php";
require "haeKesto.php";

$json = file_get_contents($argv[1]);
$a = json_decode(($json));
$pysakit = $a->pysakit;
$tiet = $a->tiet;
$linjastot = $a->linjastot;
$edges = new EdgesModel($db, $log);
$qset = new QsetModel($db, $log);

try {
    $edges->truncate();
    $qset->truncate();

    foreach ($linjastot as $linja => $linjan_pysakit) {
        foreach ($linjan_pysakit as $i => $pysakki) {
            if ($i == 0) {
                $src=$pysakki;
                continue;
            }
            $dst=$pysakki;
            $kesto=haeKesto($tiet, $src, $dst);
            $data = [
                EdgesModel::LINE=>$linja,
                EdgesModel::SRC=>$src,
                EdgesModel::DST=>$dst,
                EdgesModel::COST=>$kesto
            ];
            $edges->upsert($data);
            $src=$pysakki;
        }
    }
} catch (\mosBase\DatabaseException $de) {
    die($de->getMessage());
}
