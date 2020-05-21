<?php
namespace KOODIHAASTE;

class vPage
{
    protected $twig;
    protected $variables;
    protected $conf;

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
    
    public function nayta(string $template)
    {
        $this->twig->load($template);
        echo $this->twig->render($template, $this->variables);
    }
}
