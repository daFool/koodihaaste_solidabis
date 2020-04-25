#!/usr/bin/php
<?php
namespace KOODIHAASTE;

if ($argc != 2) {
    die("${argv[0]}: <tiedosto> ($argc)".PHP_EOL);
}

require "startup.php";

function haeKesto(array $tiet, string $src, string $dst): int {
    foreach($tiet as $tie) {
        if($tie->mista==$src && $tie->mihin==$dst) {
            return $tie->kesto;
        }
        if($tie->mista==$dst && $tie->mihin==$src) {
            return $tie->kesto;
        }
    }
    return -1;
}

$json = file_get_contents($argv[1]);
$a = json_decode(($json));
$pysakit = $a->pysakit;
$tiet = $a->tiet;
$linjastot = $a->linjastot;
$edges = new edges($db, $log);
$qset = new qset($db, $log);

try {
    $edges->truncate();
    $qset->truncate();

    foreach($linjastot as $linja=>$linjan_pysakit) {
        foreach($linjan_pysakit as $i=>$pysakki) {
            if ($i == 0) {
                $src=$pysakki;
                continue;
            }
            $dst=$pysakki;
            $kesto=haeKesto($tiet, $src, $dst);
            $data = [
                edges::LINE=>$linja,
                edges::SRC=>$src,
                edges::DST=>$dst,
                edges::COST=>$kesto
            ];
            $edges->upsert($data);
            $src=$pysakki;
        }
    }
}
catch(\mosBase\DatabaseException $de) {
    die($de->getMessage());
}