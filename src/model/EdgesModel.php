<?php
/**
 * Edges
 *
 * PHP version 7.2
 *
 * Koodihaasteen kartta.
 *
 * @category  Model
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
namespace KOODIHAASTE;

/**
 * EdgesModel
 *
 * Rajapinta EdgesModel-tauluun
 *
 * @category  Model
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
class EdgesModel extends \mosBase\Malli
{
    public const EDGES="edges";
    public const SRC="src";
    public const DST="dst";
    public const COST="cost";
    public const LINE="line";
    public const PKEY="p";
    public const NODE="node";

    /**
     * Konstruktori
     *
     * @param \mosBase\Database $db     Tietokanta
     * @param \mosBase\Log      $log    Logi
     */
    public function __construct(\mosBase\Database $db, \mosBase\Log $log)
    {
        $taulu = EdgesModel::EDGES;
        $avaimet = array(
            EdgesModel::PKEY=>array(EdgesModel::SRC,EdgesModel::DST,EdgesModel::COST)
        );
        parent::__construct($db, $log, $taulu, $avaimet);
    }

    /**
     * Upsert
     *
     * Varmistaa, että mikäli kaari pysäkkien välillä on jo olemassa, että lisätään vain
     * uusi linja, jolla sama kaari voidaan kulkea.
     *
     * @param array $data   Lisättävät tai päivitettävät tiedot
     *
     * @uses \mosBase\Malli::exists() \mosBase\Malli::upsert()
     */
    public function upsert(array $data)
    {
        if ($this->exists($data)) {
            $old = $this->give();
            $oldLines = $old[EdgesModel::LINE];
            if (array_search($data[EdgesModel::LINE], $oldLines)===false) {
                $oldLines[]=$data[EdgesModel::LINE];
                $data[EdgesModel::LINE]=$oldLines;
            }
        } else {
            $a = $data[EdgesModel::LINE];
            if (!is_array(($a))) {
                $a = array($a);
            }
            $data[EdgesModel::LINE]=$this->arrayToString($a);
        }
        return parent::upsert($data);
    }

    /**
     * Tyhjentää EdgesModel-taulun
     *
     * Tekee truncate operaation EdgesModel-taululle.
     */
    public function truncate()
    {
        $s = sprintf("truncate table %s;", EdgesModel::EDGES);
        $st=$this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
    }

    /**
     * Etsii pysäkit
     *
     * Kutsuu kannan getNodes()-funktiota palauttaakseen kaikki pysäkit.
     *
     * @return array Kaikki pysäkit
     */
    public function getNodes()
    {
        $s = "select node from getNodes() order by node asc;";
        $st = $this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Etsii reitit pysäkkien välillä
     *
     * Palauttaa kaikki kaaret pysäkkien välillä
     *
     * @return array src - lähtöpysäkki, dst - kohdepysäkki, cost - etäisyys, color - linja
     */
    public function getEdges()
    {
        $s = "select src, dst, cost, color from edges, unnest(line) color;";
        $st = $this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }
}
