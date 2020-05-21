<?php
/**
 * Dijkstra
 *
 * PHP version 7.2
 *
 * Varsinainen rajapinta tietokannan saman nimiseen funktioon.
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
 * DijkstraModel
 *
 * Varsinainen rajapinta tietokannan saman nimiseen funktioon.
 *
 * @category  Model
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 * @uses      \mosBase\Pgsql
 */
 
class DijkstraModel
{
    /**
     * @var \mosBase\Database   $db             Tietokantayhteys
     * @var \mosBase\Log        $log            Logi
     * @var bool                $inTransaction  Ollaanko transaktion sisällä?
     */
    private $db;
    private $log;
    private $inTransaction;

    public const BUSSTOP="vertex";
    public const DISTANCE="dist";
    public const LINES="line";
    public const PREVSTOP="prev";
    public const FROM="from";
    public const TO="to";
    public const WITH="with";
    public const FOR="for";
    public const TRAVELED="traveled";
    public const SAMEMETHOD="ihmetellen";

    use \mosBase\Pgsql;

    /**
     * Constructor
     *
     * @param \mosBase\Database $db     Tietokantayhteys
     * @param \mosBase\Log      $log    Logi
     */
    public function __construct(\mosBase\Database $db, \mosBase\Log $log)
    {
        $this->db = $db;
        $this->log = $log;
        $this->inTransaction = false;
    }

    /**
     * Reitin hakeminen Dijkstran algoritmilla
     *
     * Kutsuu tietokannan funktiota DijkstraModel() hakeakseen reitin. Kutsuu
     * tietokannan funktiota reverse() kääntääkseen tulosjoukon esitettävään järjestykseen.
     * @param string $from  Lähtöpysäkki
     * @param string $to    Kohdepysäkki
     *
     * @return array [ TRUE|FALSE, FALSE|reitti ] jos haku tuotti tuloksen, on ensimmäinen TRUE ja toisessa reitti.
     *
     * @uses qset::cleanup()
     */
    public function route(string $from, string $to) : array
    {
        $res = [ false, false];
        $started=false;
        if (!$this->inTransaction) {
            $started=true;
            $this->db->beginTransaction();
        }
        $s = "select dijkstra(:from, :to)";
        $st=$this->pdoPrepare($s, $this->db);
        $data = [ "from"=>$from, "to"=>$to];
        $this->pdoExecute($st, $data);
        $row = $st->fetch(\PDO::FETCH_ASSOC);
        
        $id = $row["dijkstra"];
        $s = "select * from reverse(:to, :id);";
        $st = $this->pdoPrepare($s, $this->db);
        $data = ["to"=>$to, "id"=>$id ];
        $this->pdoExecute($st, $data);
        $res=[ true, $st->fetchAll(\PDO::FETCH_ASSOC)];
        
        $q = new QsetModel($this->db, $this->log);
        $q->cleanup($id);
        if ($started) {
            $this->db->commit();
        }
        return $res;
    }

    /**
     *
     * Ratkaisu
     *
     * Yhdistää reitityksen ja ratkaisun tuloksen käsittelyn
     *
     * @param   string  $from   Lähtöpysäkki
     * @param   string  $to     Kohdepysäkki
     *
     */
    public function solve(string $from, string $to)
    {
        $this->db->beginTransaction();
        $this->inTransaction=true;
        $res = $this->processResult($this->route($from, $to));
        $this->db->commit();
        $this->inTransaction=false;
        return $res;
    }

    /**
     * Linja-optimointi ja Postgresql-taulukon purku
     *
     * Samalla kun puretaan postgresql-line-taulukko, katsellaan millä
     * värillä (linjan väri) on tähän mennessä liikuttu ja millä väreillä voitaisiin jatkaa.
     * Pyritään jatkamaan samalla/samoilla linjoilla eteenpäin kuin millä on tultu.
     *
     * @param array $res    Tulosjoukko
     *
     * @return array Siistitty tulosjoukko
     */
    public function processResult(array $res) : array
    {
        $curColor=[];
        $from="";
        $route=[];
        foreach ($res[1] as $o => $stop) {
            switch ($o) {
                case 0:
                    $from=$stop[DijkstraModel::BUSSTOP];
                    $prevDist=$stop[DijkstraModel::DISTANCE];
                    $route[0]=[
                        DijkstraModel::FROM=>$from,
                        DijkstraModel::TO=>$from,
                        DijkstraModel::WITH=>array(self::SAMEMETHOD),
                        DijkstraModel::FOR=>0,
                        DijkstraModel::TRAVELED=>0
                    ];
                    break;
                case 1:
                    $curColor=$this->pg_array_parse($stop[DijkstraModel::LINES]);
                    $to=$stop[DijkstraModel::BUSSTOP];
                    $dist=$stop[DijkstraModel::DISTANCE];
                    $route[0]=[
                        DijkstraModel::FROM=>$from,
                        DijkstraModel::TO=>$to,
                        DijkstraModel::WITH=>$curColor,
                        DijkstraModel::FOR=>$dist-$prevDist,
                        DijkstraModel::TRAVELED=>$dist
                    ];
                    $from=$to;
                    $prevDist=$dist;
                    break;
                default:
                    $c=$this->pg_array_parse($stop[DijkstraModel::LINES]);
                    $potColor=array_intersect($c, $curColor);
                    if (empty($potColor)) {
                        $curColor=$c;
                    } else {
                        $curColor=$potColor;
                    }
                    $to=$stop[DijkstraModel::BUSSTOP];
                    $dist=$stop[DijkstraModel::DISTANCE];
                    $route[]=[
                        DijkstraModel::FROM=>$from,
                        DijkstraModel::TO=>$to,
                        DijkstraModel::WITH=>$curColor,
                        DijkstraModel::FOR=>$dist-$prevDist,
                        DijkstraModel::TRAVELED=>$dist
                    ];
                    $from=$to;
                    $prevDist=$dist;
                    break;
            }
        }
        return $route;
    }
}
