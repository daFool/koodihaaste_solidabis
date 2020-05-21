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

        $this->variables["baseUrl"] = $this->conf->get("General")["baseUrl"];
        $this->variables["basePath"]= $this->conf->get("General")["basePath"];
        $this->variables["backendUrl"]= $this->conf->get("General")["backEndUrl"];
        $this->variables["xsize"]= $reittikartta["xsize"];
        $this->variables["ysize"]= $reittikartta["ysize"];
        $this->variables["direction"]= $reittikartta["direction"];
        $this->variables["levelSeparation"]= $reittikartta["levelSeparation"];
        $this->variables["nodeSpacing"]= $reittikartta["nodeSpacing"];
        $this->variables["tDirection"]= $tuloskartta["direction"];
        $this->variables["tLevelSeparation"]= $tuloskartta["levelSeparation"];
        $this->variables["tNodeSpacing"]= $tuloskartta["nodeSpacing"];
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
