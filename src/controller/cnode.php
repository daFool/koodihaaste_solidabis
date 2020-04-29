<?php
namespace KOODIHAASTE;

class cnode extends controller {
    public const ID="id";
    public const LABEL="label";

    public function get($f3) {
        parent::get($f3);

        $nodes = $this->edges->getNodes();
        /**
         * vis:iä varten nodet pitää muotoilla jsoniksi:
         * id: juokseva numero, label: 'Pysäkin nimi'
         * Juokseva numero on helpointa kaaria varten ajatella niin, että A on yksi
         * ja siitä eteenpäin aakkosjärjestyksessä
         */
        $nodebase = [];
        foreach($nodes as $id=>$label) {
            $nodebase[]=[ self::ID=>$this->map[$label[edges::NODE]], self::LABEL =>$label[edges::NODE] ];
        }
        $this->json($nodebase);

    }
}
