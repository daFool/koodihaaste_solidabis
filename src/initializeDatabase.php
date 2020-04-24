#!/usr/bin/php
<?php
if ($argc != 2) {
    die("${argv[0]}: <tiedosto> ($argc)".PHP_EOL);
}

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
printf("truncate table edges;%s", PHP_EOL);
printf("truncate table qset;%s", PHP_EOL);
foreach($linjastot as $linja=>$linjan_pysakit) {
    foreach($linjan_pysakit as $i=>$pysakki) {
        if ($i == 0) {
            $src=$pysakki;
            continue;
        }
        $dst=$pysakki;
        $kesto=haeKesto($tiet, $src, $dst);
        printf("insert into edges(line, src, dst, cost) values ('{\"%s\"}', '%s','%s', %d);%s", $linja, $src, $dst, $kesto, PHP_EOL);
        $src=$pysakki;
    }
}