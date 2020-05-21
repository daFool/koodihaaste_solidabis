<?php
/**
 * haeKesto
 *
 * PHP version 7.2
 *
 * Pääohjelma komentorivi-ratkaisimelle.
 *
 * @category  Commandline
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */

 /**
  * haeKesto
  *
  * Etsii tiet-taulusta kaaren, joka yhdistää pysäkit toisiinsa ja palauttaa keston.
  *
  * @param array $tiet  Tiet, joista etsitään
  * @param string $src  Lähtöpysäkki
  * @param string $dst  Kohdepysäkki
  *
  * @return int Joko -1, jos ei ole reittiä tai reitin pituus
  */
function haeKesto(array $tiet, string $src, string $dst): int
{
    foreach ($tiet as $tie) {
        if ($tie->mista==$src && $tie->mihin==$dst) {
            return $tie->kesto;
        }
        if ($tie->mista==$dst && $tie->mihin==$src) {
            return $tie->kesto;
        }
    }
    return -1;
}
