<?php
/**
 * Controller
 *
 * PHP version 7.2
 *
 * Kaikille controllereille yhteiset toiminnot
 *
 * @category  Controller
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */

namespace KOODIHAASTE;

/**
 * Controller
 *
 * Controllerien perusluokka
 *
 * @category  Controller
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 *
 */
abstract class Controller
{
    /**
     * @var \mosBase\Database   $db     T   ietokanta
     * @var \mosBase\Log        $log        Logi
     * @var \mosBase\Config     $conf       Konfiguraatio
     * @var Object              $f3         Fat Free Core
     * @var DijkstraModel       $dijkstra   Malli
     * @var EdgesModel          $edges      Pysäkit ja linjat
     * @var array               $map        Kirjaimesta numeroksi
     * @var array               $cmap       Suomalaisesta väristä englanniksi
     */
    protected $db;
    protected $log;
    protected $conf;
    protected $f3;

    protected $dijkstra;
    protected $edges;

    protected $map;
    protected $cmap;

    public const CHECKRE='/[A-R]{1}/';

    /**
     * Konstruktori
     *
     * Luo kirjain->numero- ja väri->enkkuväri- mäppäystaulut
     */
    public function __construct()
    {
        $a = range('A', 'Z');
        foreach ($a as $i => $l) {
            $this->map[$l]=$i+1;
        }
        $this->cmap = [
            "vihreä"=>"green",
            "keltainen"=>"yellow",
            "punainen"=>"red",
            "sininen"=>"blue"
        ];
    }
  
    /**
     * Asettaa luokkamuuttujat
     *
     * Hakee Fat Free Coren "pankista" kannan, login ja konfiguraation. Perustaa ratkaisu- ja kartta-oliot.
     * @param object    $f3 Fat Free Core
     */
    protected function init($f3)
    {
        $this->f3 = $f3;
        $this->db = $f3->get("db");
        $this->log = $f3->get("log");
        $this->conf = $f3->get("conf");

        $this->dijkstra = new DijkstraModel($this->db, $this->log);
        $this->edges = new EdgesModel($this->db, $this->log);
    }

    /**
     * get-operaatiot
     *
     * Huolehtii siitä, että luokkamuuttujat alustetaan
     *
     * @param object    $f3 Fat Free Core
     */
    public function get($f3)
    {
        $this->init($f3);
    }

    /**
     * JSON-palaute
     *
     * Tulostaa json-palautteen ja sen tarvitseman http(s) content-headerin
     *
     * @param mixed $v  Jsoniksi-muutettava rakenne, yleensä array
     *
     */
    public function json($v)
    {
        header("Content-type: application/json");
        echo json_encode($v);
    }
}
