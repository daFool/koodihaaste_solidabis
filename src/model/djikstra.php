<?php
namespace KOODIHAASTE;

class djikstra {
    private $db;
    private $log;

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

    public function __construct(\mosBase\Database $db, \mosBase\Log $log) {
        $this->db = $db;
        $this->log = $log;
    }

    public function route(string $from, string $to) : array {
        $res = [ FALSE, FALSE];
        $s = "select djikstra(:from, :to)";
        $st=$this->pdoPrepare($s, $this->db);
        $data = [ "from"=>$from, "to"=>$to];
        $this->pdoExecute($st, $data);
        $row = $st->fetch(\PDO::FETCH_ASSOC);
        $id = $row["djikstra"];
        $s = "select * from reverse(:to, :id);";
        $st = $this->pdoPrepare($s, $this->db);
        $data = ["to"=>$to, "id"=>$id ];
        $this->pdoExecute($st, $data);
        $res=[ TRUE, $st->fetchAll(\PDO::FETCH_ASSOC)];
        $q = new qset($this->db, $this->log);
        $q->cleanup($id);
        return $res;
    }

    public function processResult(array $res) : array {
        $curColor=[];
        $from="";
        $route=[];
        foreach($res[1] as $o=>$stop) {
            switch($o) {
                case 0:
                    $from=$stop[djikstra::BUSSTOP];
                    $prevDist=$stop[djikstra::DISTANCE];
                    $route[0]=[ 
                        djikstra::FROM=>$from, 
                        djikstra::TO=>$from, 
                        djikstra::WITH=>array(self::SAMEMETHOD),
                        djikstra::FOR=>0,
                        djikstra::TRAVELED=>0 
                    ];
                break;
                case 1:
                    $curColor=$this->pg_array_parse($stop[djikstra::LINES]);
                    $to=$stop[djikstra::BUSSTOP];
                    $dist=$stop[djikstra::DISTANCE];
                    $route[0]=[ 
                        djikstra::FROM=>$from, 
                        djikstra::TO=>$to,
                        djikstra::WITH=>$curColor,
                        djikstra::FOR=>$dist-$prevDist,
                        djikstra::TRAVELED=>$dist 
                    ];
                    $from=$to;
                    $prevDist=$dist;
                break;
                default:
                    $c=$this->pg_array_parse($stop[djikstra::LINES]);
                    $potColor=array_intersect($c, $curColor);
                    if(empty($potColor)) {
                        $curColor=$c;
                    } else {
                        $curColor=$potColor;
                    }
                    $to=$stop[djikstra::BUSSTOP];
                    $dist=$stop[djikstra::DISTANCE];
                    $route[]=[ 
                        djikstra::FROM=>$from, 
                        djikstra::TO=>$to,
                        djikstra::WITH=>$curColor,
                        djikstra::FOR=>$dist-$prevDist,
                        djikstra::TRAVELED=>$dist 
                    ];
                    $from=$to;
                    $prevDist=$dist;
                break;
            }
        }
        return $route;
    }
}