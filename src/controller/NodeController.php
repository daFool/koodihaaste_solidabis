<?php
/**
 * Node-kontrolleri
 *
 * PHP version 7.2
 *
 * Pysäkille yhteiset toiminnot
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
 * NodeController
 *
 * Ratkaisun pysäkit
 *
 * @category  Controller
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */

class NodeController extends Controller
{
    public const ID="id";
    public const LABEL="label";

    /**
     * Get-operaatiot
     *
     * Pysäkit
     *
     * @param object    $f3 Fat Free Core
     *
     * @uses json()
     */
    public function get($f3)
    {
        parent::get($f3);

        $nodes = $this->edges->getNodes();
        /**
         * vis:iä varten nodet pitää muotoilla jsoniksi:
         * id: juokseva numero, label: 'Pysäkin nimi'
         * Juokseva numero on helpointa kaaria varten ajatella niin, että A on yksi
         * ja siitä eteenpäin aakkosjärjestyksessä
         */
        $nodebase = [];
        foreach ($nodes as $id => $label) {
            $nodebase[]=[ self::ID=>$this->map[$label[EdgesModel::NODE]], self::LABEL =>$label[EdgesModel::NODE] ];
        }
        $this->json($nodebase);
    }
}
