<?php
/**
 * Sivutemplatejen esittäminen
 *
 * PHP version 7.2
 *
 * Sivutemplatejen käsittely
 *
 * @category  View
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
namespace KOODIHAASTE;

/**
 * Sivutemplaten esittäminen
 *
 * Twig-tempalateista koostuvan sivun esittäminen
 *
 * @category  View
 * @package   Koodihaaste
 * @author    Mauri "Fuula-setä" Sahlberg <fuula@generalfailure.net>
 * @copyright 2020 Mauri Sahlberg, Helsinki
 * @license   GPL-2.0 http://opensource.org/licenses/GPL-2.0
 * @link      https://github.com/daFool/koodihaaste_solidabis
 */
class PageView
{
    /**
     * @var \Twig\Environment   $twig       Twig-template-engine
     * @var array               $variables  Twig-template-muuttujat
     * @var \mosBase\Config     $conf       Konfiguraatio
     *
    */
    protected $twig;
    protected $variables;
    protected $conf;

    public const GENERAL="General";
    public const LSEP="LevelSeparation";
    public const NSEP="NodeSpacing";
    public const DIR="Direction";
    /**
     * Konstruktori
     *
     * Alustaa luokkamuuttujat ja twig-templaten muuttujat.
     *
     * @param \Twig\Environment $twig   Twig-template-engine
     * @param array             $v      Twig-templaten-muuttujat
     * @param \mosBase\Config   $conf   Konfiguraatio
     *
     */
    public function __construct(\Twig\Environment $twig, array $v, \mosBase\Config $conf)
    {
        $this->twig = $twig;
        $this->variables = $v;
        $this->conf = $conf;
        $reittikartta = $conf->get("Reittikartta");
        $tuloskartta = $conf->get("Tuloskartta");

        $this->variables["baseUrl"] = $this->conf->get(self::GENERAL)["baseUrl"];
        $this->variables["basePath"]= $this->conf->get(self::GENERAL)["basePath"];
        $this->variables["backendUrl"]= $this->conf->get(self::GENERAL)["backEndUrl"];
        $this->variables["xsize"]= $reittikartta["xsize"];
        $this->variables["ysize"]= $reittikartta["ysize"];
        $this->variables[self::DIR]= $reittikartta[self::DIR];
        $this->variables[self::LSEP]= $reittikartta[self::LSEP];
        $this->variables[self::NSEP]= $reittikartta[self::NSEP];
        $this->variables["tDirection"]= $tuloskartta[self::DIR];
        $this->variables["tLevelSeparation"]= $tuloskartta[self::LSEP];
        $this->variables["tNodeSpacing"]= $tuloskartta[self::NSEP];
    }

    /**
     * Sivun esittäminen twigillä
     *
     * @param string    $template   Esitettävän templaten nimi
     *
     */
    public function nayta(string $template)
    {
        $this->twig->load($template);
        echo $this->twig->render($template, $this->variables);
    }
}
