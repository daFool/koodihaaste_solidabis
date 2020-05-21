<?php
namespace KOODIHAASTE;

class controller
{
    protected $db;
    protected $log;
    protected $conf;
    protected $f3;

    protected $djikstra;
    protected $edges;

    protected $map;
    protected $cmap;

    public const CHECKRE='/[A-R]{1}/';

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
  
    protected function init($f3)
    {
        $this->f3 = $f3;
        $this->db = $f3->get("db");
        $this->log = $f3->get("log");
        $this->conf = $f3->get("conf");

        $this->djikstra = new djikstra($this->db, $this->log);
        $this->edges = new edges($this->db, $this->log);
    }

    public function get($f3)
    {
        $this->init($f3);
    }

    public function json($v)
    {
        header("Content-type: application/json");
        echo json_encode($v);
    }
}
