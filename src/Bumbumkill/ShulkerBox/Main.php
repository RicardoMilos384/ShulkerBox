<?php

namespace Bumbumkill\ShulkerBox;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

public function onEnable(){
    	$this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
}
