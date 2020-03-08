<?php

namespace Bumbumkill\ShulkerBox\utils;

use pocketmine\tile\Tile;

abstract class tile extends Tile{


   public const
	SHULKER_BOX = "ShulkerBox";

   public static function init(){
     self::registerTile(MobSpawner::class);
   }
}

