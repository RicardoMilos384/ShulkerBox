<?php

namespace Bumbumkill\ShulkerBox\tile;

use Bumbumkill\ShulkerBox\Inventory\shulkerinv;
use pocketmine\inventory\InventoryHolder;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Container;
use pocketmine\tile\ContainerTrait;
use pocketmine\tile\Nameable;
use pocketmine\tile\NameableTrait;
use pocketmine\tile\Spawnable;

class ShulkerBox extends Spawnable implements InventoryHolder, Container, Nameable {
	use NameableTrait, ContainerTrait;
  
  protected $inventory;

	public function getDefaultName(): string{
		return "Shulker Box";
	}

	public function close(): void{
		if(!$this->isClosed()){
			$this->inventory->removeAllViewers(true);
			$this->inventory = null;
			parent::close();
		}
	}

	public function getRealInventory(){
		return $this->inventory;
	}

	public function getInventory(){
		return $this->inventory;
	}

	protected function readSaveData(CompoundTag $nbt): void{
		$this->loadName($nbt);
		$this->inventory = new shulkerinv($this);
		$this->loadItems($nbt);
	}

	protected function writeSaveData(CompoundTag $nbt): void{
		$this->saveName($nbt);
		$this->saveItems($nbt);
	}
}



