<?php
namespace KOODIHAASTE;

class edges extends \mosBase\Malli {
    public const EDGES="edges";
    public const SRC="src";
    public const DST="dst";
    public const COST="cost";
    public const LINE="line";
    public const PKEY="p";
    public const NODE="node";
    public function __construct(\mosBase\Database $db, \mosBase\Log $log) {
        $taulu = edges::EDGES;
        $avaimet = array(
            edges::PKEY=>array(edges::SRC,edges::DST,edges::COST)
        );
        parent::__construct($db, $log, $taulu, $avaimet);
    }

    public function upsert(array $data) {
        if($this->exists($data)) {
            $old = $this->give();
            $oldLines = $old[edges::LINE];
            if(array_search($data[edges::LINE], $oldLines)===FALSE) {
                $oldLines[]=$data[edges::LINE];
                $data[edges::LINE]=$oldLines;
            }
        } else {
            $a = $data[edges::LINE];
            if(!is_array(($a))) {
                $a = array($a);
            }
            $data[edges::LINE]=$this->arrayToString($a);
        }
        return parent::upsert($data);
    }

    public function truncate() {
        $s = sprintf("truncate table %s;", edges::EDGES);
        $st=$this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
    }

    public function getNodes() {
       $s = "select * from getNodes() order by node asc;";
       $st = $this->pdoPrepare($s, $this->db);
       $this->pdoExecute($st);
       return $st->fetchAll(\PDO::FETCH_ASSOC); 
    }

    public function getEdges() {
        $s = "select src, dst, cost, color from edges, unnest(line) color;";
        $st = $this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }
}