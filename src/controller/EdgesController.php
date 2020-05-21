<?php
/**
 * Edges-kontrolleri
 *
 * PHP version 7.2
 *
 * Kaarille yhteiset toiminnot
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
 * EdgesController
 *
 * Ratkaisun kaaret
 *
 * @category  Controller
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
class EdgesController extends Controller
{
   
    public const TO="to";
    public const FROM="from";
    public const COLOR="color";
    
    /**
     * Get
     *
     * Get-operaatiot
     *
     * @param object $f3    Fat Free Core
     * @uses json()
     */
    public function get($f3)
    {
        parent::get($f3);

        $edges = $this->edges->getEdges();
       
        /**
         * vis:iä varten edget pitää muotoilla:
         *    {from: 1, to: 3, color: 'green', label: '5'},
         * from: noden numeroid
         * to: noden numeroid
         **/
        $edgebase = [];
        foreach ($edges as $edge) {
            $edgebase[]= [
                self::FROM => $this->map[$edge[EdgesModel::SRC]],
                self::TO => $this->map[$edge[EdgesModel::DST]],
                self::COLOR => $this->cmap[$edge[self::COLOR]],
                NodeController::LABEL => $edge[EdgesModel::COST]
            ];
        }
        $this->json($edgebase);
    }
}
