<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
use Smarty;
class SmartyLocal {
    private Smarty $smarty;
    public function __construct() {
        $this->smarty = new Smarty();
    }
    public function getSmarty(): Smarty {
        return $this->smarty;
    }
    public function initialize(bool $debug) {
        $this->smarty->setTemplateDir(template_dir: "templates/smarty");
        $this->smarty->setCompileDir(compile_dir: "templates/smarty/compiled");
        $this->smarty->setCacheDir(cache_dir: "classes/common/smarty/cache");
        $this->smarty->setConfigDir(config_dir: "classes/common/smarty/configs");
        $this->smarty->setDebugging(debugging: $debug);
    }
    public function setSmarty(Smarty $smarty) {
        $this->smarty = $smarty;
        return $this;
    }
}