<?php

namespace Bumbumkill\ShulkerBox\block;

use Bumbumkill\ShulkerBox\tile\{ShulkerBox as ShulkerTile, tile as Tile};
use pocketmine\block\{Block, BlockToolType, Transparent};
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\math\Vector3;
use Bumbumkill\ShulkerBox\Main;
use pocketmine\tile\Container;

class ShulkerBox extends Transparent {

	public function __construct(int $id = self::SHULKER_BOX, int $meta = 0){
		$this->id = $id;
		$this->meta = $meta;
	}

	public function getResistance(): float{
		return 30;
	}

	public function getHardness(): float{
		return 2;
	}

	public function getToolType(): int{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getName(): string{
		return "Shulker Box";
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		$nbt = ShulkerTile::createNBT($this, $face, $item, $player);
		$items = $item->getNamedTag()->getTag(Container::TAG_ITEMS);
		if($items !== null){
                  $nbt->setTag($items);
		}
		 Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), $nbt);
		 ($inv = $player->getInventory())->clear($inv->getHeldItemIndex());
		 return true;
	}

	public function onBreak(Item $item, Player $player = null): bool{
                 /** @var ShulkerTile $tile */
		$tile = $this->getLevel()->getTile($this);
		if($tile instanceof ShulkerTile){
	          $item = ItemFactory::get($this->id, $this->id != self::UNDYED_SHULKER_BOX ? $this->meta : 0, 1);
		  $itemNBT = clone $item->getNamedTag();
		  $itemNBT->setTag($tile->getCleanedNBT()->getTag(Container::TAG_ITEMS));
		  $item->setNamedTag($itemNBT);
		  $this->getLevel()->dropItem($this->add(0.5,0.5,0.5), $item);
		  $tile->getInventory()->clearAll();
		}
		$this->getLevel()->setBlock($this, Block::get(Block::AIR), true, true);

		return true;
	}

	public function onActivate(Item $item, Player $player = null): bool{
		if(Main::$shulkerEnable){
			if($player instanceof Player){
				$tile = $this->getLevel()->getTile($this);
				if(!($tile instanceof ShulkerTile)){
				  $tile = Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), ShulkerTile::createNBT($this));
				}
				  $player->addWindow($tile->getInventory());
			}
		}

		return true;
	}

	public function getDrops(Item $item): array{
		return [];
	}
}
