<?php
namespace KOODIHAASTE;

class qset extends \mosBase\Malli {
    public const QSET="qset";
    public const VERTEX="vertex";
    public const DIST="dist";
    public const PREV="prev";
    public const VISITED="visited";
    public const LINE="line";
    public const RUN="run";
    public const PKEY="p";

    public function __construct(\mosBase\Database $db, \mosBase\Log $log) {
        $taulu = qset::QSET;
        $avaimet = array(
            qset::PKEY=>array(qset::VERTEX,qset::RUN)
        );
        parent::__construct($db, $log, $taulu, $avaimet);
    }

    public function truncate() {
        $s = sprintf("truncate table %s;", qset::QSET);
        $st=$this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
    }

    public function cleanup(int $id) {
        $s = sprintf("delete from qset where run=:id;");
        $st = $this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st, ["id"=>$id]);
    }
}