<?php
/**
 * DijkstraController
 *
 * PHP version 7.2
 *
 * Ratkaisun backend-rajapinta
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
 * DijkstraController
 *
 * Ratkaisun backend-rajapinta
 *
 * @category  Controller
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
class DijkstraController extends Controller
{

    /**
     * Get
     *
     * http(s)-get-operaatio. Varsinainen ratkaisu, olettaa että from-parametrissa on
     * lähtöpysäkki ja to-parametrissa on kohdepysäkki. Palauttaa ratkaisureitin jsonina.
     *
     * @param object    $f3 Fat Free Core
     *
     * @uses addNode() addEdge() DijkstraModel::solve() json()
     */
    public function get($f3)
    {
        parent::get($f3);

        $from = $_REQUEST["from"]??false;
        $to = $_REQUEST["to"]??false;

        $res = [ false, _("Jokin meni mönkään.") ];

        if ($from===false || !preg_match(self::CHECKRE, $from)) {
            $res[1]=_("from-parameteri puuttuu tai on huono.");
            $this->json($res);
            return;
        }
        if ($to===false || !preg_match(self::CHECKRE, $to)) {
            $res[1]=_("to-paremetri puuttuu tai on huono.");
            $this->json($res);
            return;
        }
        $route = $this->dijkstra->solve($from, $to);
        $nodes=[];
        $edges=[];
        $steps=[];

        foreach ($route as $i => $step) {
            $nodes=$this->addNode($step[DijkstraModel::FROM], $nodes);
            $nodes=$this->addNode($step[DijkstraModel::TO], $nodes);
            foreach ($step[DijkstraModel::WITH] as $color) {
                $edges=$this->addEdge(
                    $step[DijkstraModel::FROM],
                    $step[DijkstraModel::TO],
                    $color,
                    $step[DijkstraModel::FOR],
                    $edges
                );
            }
            $steps[$i]=$step;
            $c = $this->color($route, $i, $step[DijkstraModel::WITH]);
            if (is_null($c) || empty($c)) {
                $res[1]=_("Ohjelmointivirhe. Värijoukko on tyhjä tai null.");
                $this->json($res);
                return;
            }
            $steps[$i][DijkstraModel::WITH]=$c;
        }
        $res = [ true, $nodes, $edges, $steps ];
        $this->json($res);
    }

    /**
     * Linjavaihtojen optimointi
     *
     * Pyrkii valitsemaan sen värisen linjan, jolla syntyy vähiten vaihtoja
     *
     * @param array $route  Löydetty reitti eri linjoineen
     * @param int   $i      Kohta reitillä missä ollaan menossa
     * @param array $curset Nyt käytössä olevat linjavaihtoehdot
     *
     * @return array    Joukko mahdollisia linjoja, joilla syntyy vähiten vaihtoja
     */
    private function color(array $route, int $i, array $curset)
    {
        $oset=$curset;
        for ($i++; $i<count($route); $i++) {
            if (is_null($curset) || empty($curset)) {
                return $route[$i-1][DijkstraModel::WITH];
            }
            $a = array_intersect($curset, $route[$i][DijkstraModel::WITH]);
            if (empty($a) || is_null($a)) {
                reset($curset);
                return $curset[key($curset)];
            }
            if (count($a)==1) {
                return $a[key($a)];
            }
            $curset=$a;
        }
        reset($oset);
        return $oset[key($oset)];
    }
 
    /**
     * Onko annettu pysäkki ja pysäkkien luettelossa?
     *
     * Käy lävitse annetun pysäkki-taulukon ja tutkii onko pysäkki jo luettelossa.
     *
     * @param   string  $letter Etsittävän pysäkin kirjain
     * @param   array   $nodes  Pysäkki-taulu, josta etsitään
     *
     * @return  bool    Palauttaa true, jos pysäkki löytyi, false jos ei.
     */
    private function hasNode(string $letter, array $nodes) : bool
    {
        if (empty($nodes)) {
            return false;
        }
        foreach ($nodes as $node) {
            if ($node[NodeController::LABEL]==$letter) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Pysäkin lisääminen tauluun
     *
     * Lisää pysäkin pysäkkitauluun, jos se ei jo ollut siellä.
     *
     * @param string    Lisättävä pysäkki
     * @param array     Taulu johon lisätään
     *
     * @return  array   Palauttaa uuden pysäkkitaulun
     */
    private function addNode(string $letter, array $nodes)
    {
        if ($this->hasNode($letter, $nodes)) {
            return $nodes;
        }
        $nodes[]=[
            NodeController::ID=>$this->map[$letter],
            NodeController::LABEL=>$letter
        ];
        return $nodes;
    }

    /**
     * Kaaren lisääminen tauluun
     *
     * Lisää annetun kaaren annetulla värillä ja kustannuksella kaarien tauluun
     *
     * @param string    $from   Lähtöpysäkki
     * @param string    $to     Kohdepysäkki
     * @param string    $color  Väri suomeksi
     * @param string    $cost   Kesto pysäkiltä pysäkille
     * @param array     $array  Nykyiset kaaret
     *
     * @return array    Uusi kaaritaulu
     */
    private function addEdge(string $from, string $to, string $color, string $cost, array $edges)
    {
        $edges[]=[
            EdgesController::FROM=>$this->map[$from],
            EdgesController::TO=>$this->map[$to],
            EdgesController::COLOR=>$this->cmap[$color],
            NodeController::LABEL=> $cost
        ];
        return $edges;
    }
}
