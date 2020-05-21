<?php
/**
 * QSet
 *
 * PHP version 7.2
 *
 * Ratkaisujoukko
 *
 * @category  Model
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
namespace KOODIHAASTE;

/**
 * QSet
 *
 * Ratkaisujoukko.
 *
 * @category  Model
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
class QsetModel extends \mosBase\Malli
{
    public const QSET="qset";
    public const VERTEX="vertex";
    public const DIST="dist";
    public const PREV="prev";
    public const VISITED="visited";
    public const LINE="line";
    public const RUN="run";
    public const PKEY="p";

    /**
     * Konstruktori
     *
     * @param \mosBase\Database $db Tietokanta
     * @param \mosBase\Log      $log    Logi
     */
    public function __construct(\mosBase\Database $db, \mosBase\Log $log)
    {
        $taulu = QsetModel::QSET;
        $avaimet = array(
            QsetModel::PKEY=>array(QsetModel::VERTEX,QsetModel::RUN)
        );
        parent::__construct($db, $log, $taulu, $avaimet);
    }

    /**
     * Tyhjentää QsetModel-taulun
     *
     * Suorittaa truncaten QsetModel-taululle.
     */
    public function truncate()
    {
        $s = sprintf("truncate table %s;", QsetModel::QSET);
        $st=$this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st);
    }

    /**
     * Poistaa id:n osoittaman ajon rivit
     *
     * Tekee deleten qset-taululle ajon tunnisteella.
     *
     * @param int $id   Ajon tunniste
     */
    public function cleanup(int $id)
    {
        $s = sprintf("delete from qset where run=:id;");
        $st = $this->pdoPrepare($s, $this->db);
        $this->pdoExecute($st, ["id"=>$id]);
    }
}
