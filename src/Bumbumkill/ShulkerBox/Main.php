<?php

namespace Bumbumkill\ShulkerBox;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use Bumbumkill\ShulkerBox\tile\ShulkerBox as ShulkerTile;
use pocketmine\tile\Tile;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use Bumbumkill\ShulkerBox\block\ShulkerBox;

class Main extends PluginBase implements Listener {
	
	public static $shulkerEnable = true;
	public static $config;
	
public function onLoad(){
	Tile::registerTile(ShulkerTile::class);
	BlockFactory::registerBlock(new ShulkerBox(Block::UNDYED_SHULKER_BOX), true);
	BlockFactory::registerBlock(new ShulkerBox(), true);
	Item::initCreativeItems();
   }
public function onEnable(){
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	if(!file_exists($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		$this->saveDefaultConfig();
		self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		self::$shulkerEnable = self::$config->getNested("Enable", self::$shulkerEnable);
  }
}
