<?php
namespace KOODIHAASTE;

class cedges extends controller {
   
    public const TO="to";
    public const FROM="from";
    public const COLOR="color";
    
    public function get($f3) {
        parent::get($f3);

        $edges = $this->edges->getEdges();
       
        /**
         * vis:iä varten edget pitää muotoilla:
         *    {from: 1, to: 3, color: 'green', label: '5'},
         * from: noden numeroid
         * to: noden numeroid
         **/
        $edgebase = [];
        foreach($edges as $edge) {
            $edgebase[]= [ 
                self::FROM => $this->map[$edge[edges::SRC]],
                self::TO => $this->map[$edge[edges::DST]],
                self::COLOR => $this->cmap[$edge[self::COLOR]],
                cnode::LABEL => "${edge[edges::COST]}"
            ];
        }
        $this->json($edgebase);
    }
}