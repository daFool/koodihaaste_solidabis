<?php
namespace KOODIHAASTE;

class cdjikstra extends controller {

    public function get($f3) {
        parent::get($f3);

        $from = $_REQUEST["from"]??FALSE;
        $to = $_REQUEST["to"]??FALSE;

        $res = [ FALSE, _("Jokin meni mönkään.") ];

        if($from===FALSE || !preg_match(self::CHECKRE, $from)) {
            $res[1]=_("from-parameteri puuttuu tai on huono.");
            $this->json($res);
            return;
        }
        if($to===FALSE || !preg_match(self::CHECKRE, $to)) {
            $res[1]=_("to-paremetri puuttuu tai on huono.");
            $this->json($res);
            return;
        }
        $route = $this->djikstra->processResult($this->djikstra->route($from, $to));
        $nodes=[];
        $edges=[];        
        
        foreach($route as $step) {
            $nodes=$this->addNode($step[djikstra::FROM], $nodes);
            $nodes=$this->addNode($step[djikstra::TO], $nodes);
            foreach($step[djikstra::WITH] as $color) {
                $edges=$this->addEdge($step[djikstra::FROM], $step[djikstra::TO], $color, $step[djikstra::FOR], $edges);
            }
        }
        $res = [ TRUE, $nodes, $edges ];
        $this->json($res);
    }

    private function hasNode(string $letter, array $nodes) : bool {
        if(empty($nodes)) {
            return FALSE;
        }
        foreach($nodes as $node) {
            if($node[cnode::LABEL]==$letter) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    private function addNode(string $letter, array $nodes) {
        if ($this->hasNode($letter, $nodes)) {
            return $nodes;
        }
        $nodes[]=[ 
            cnode::ID=>$this->map[$letter],
            cnode::LABEL=>$letter
        ];
        return $nodes;
    }

    private function addEdge(string $from, string $to, string $color, string $cost, array $edges) {
        $edges[]=[
            cedges::FROM=>$this->map[$from],
            cedges::TO=>$this->map[$to],
            cedges::COLOR=>$this->cmap[$color],
            cnode::LABEL=> $cost
        ];
        return $edges;
    } 
}