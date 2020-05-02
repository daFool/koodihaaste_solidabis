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
        $steps=[];

        foreach($route as $i=>$step) {
            $nodes=$this->addNode($step[djikstra::FROM], $nodes);
            $nodes=$this->addNode($step[djikstra::TO], $nodes);
            foreach($step[djikstra::WITH] as $color) {
                $edges=$this->addEdge($step[djikstra::FROM], $step[djikstra::TO], $color, $step[djikstra::FOR], $edges);
            }
            $steps[$i]=$step;
            $c = $this->color($route, $i, $step[djikstra::WITH]); 
            if(is_null($c) || empty($c)) {
                var_dump($c);
                die;
            }   
            $steps[$i][djikstra::WITH]=$c;
        }
        $res = [ TRUE, $nodes, $edges, $steps ];
        $this->json($res);
    }

    private function color(array $route, int $i, array $curset) {
        $oset=$curset;
        for($i++;$i<count($route);$i++) {
            if(is_null($curset) || empty($curset)) {
  //              echo "<br>Escape with empty curset<br>";
                return $i ? $route[$i-1][djikstra::WITH] : $route[$i][djikstra::WITH];
            }
            $a = array_intersect($curset, $route[$i][djikstra::WITH]);
            if (empty($a) || is_null($a)) {
  //              echo "<br>Escape with empty intersection<br>";
                reset($curset);
                return $curset[key($curset)];            
            }
            if(count($a)==1) {
 /*               echo "<br>Escape with A<br>";
                reset($a);
                var_dump(key($a));
                var_dump($a[key($a)]);
                echo "<br>Escape with A<br>"; */
                $c = $a[key($a)];
                // var_dump($c);
                return $c;
            }
            $curset=$a;
        }
        reset($oset);
        return $oset[key($oset)];
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