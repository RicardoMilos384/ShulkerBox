<?php

namespace Bumbumkill\ShulkerBox\block;

use Bumbumkill\ShulkerBox\tile\{ShulkerBox as ShulkeTile, tile as Tile};
use pocketmine\block\{Block, BlockToolType, Transparent};
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
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
		$nbt = ShulkeTile::createNBT($this, $face, $item, $player);
		$items = $item->getNamedTag()->getTag(Container::TAG_ITEMS);
		if($items !== null){
			$nbt->setTag($items);
		}
		Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), $nbt);

		($inv = $player->getInventory())->clear($inv->getHeldItemIndex());
		return true;
	}

	public function onBreak(Item $item, Player $player = null): bool{
		$t = $this->getLevel()->getTile($this);
		if($t instanceof ShulkeTile){
			$item = ItemFactory::get($this->id, $this->id != self::UNDYED_SHULKER_BOX ? $this->meta : 0, 1);
			$itemNBT = clone $item->getNamedTag();
			$itemNBT->setTag($t->getCleanedNBT()->getTag(Container::TAG_ITEMS));
			$item->setNamedTag($itemNBT);
			$this->getLevel()->dropItem($this->add(0.5,0.5,0.5), $item);

			$t->getInventory()->clearAll(); // dont drop the items
		}
		$this->getLevel()->setBlock($this, Block::get(Block::AIR), true, true);

		return true;
	}

	public function onActivate(Item $item, Player $player = null): bool{
		if(Main::$shulkerEnable){
			if($player instanceof Player){
				$t = $this->getLevel()->getTile($this);
				if(!($t instanceof ShulkeTile)){
					$t = Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), ShulkeTile::createNBT($this));
				}
                                  if(!$this->getSide(Vector3::SIDE_UP)->isTransparent()){
					return true;
				}
				$player->addWindow($t->getInventory());
			}
		}

		return true;
	}

	public function getDrops(Item $item): array{
		return [];
	}
}
