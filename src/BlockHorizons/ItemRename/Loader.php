<?php

declare(strict_types=1);

namespace BlockHorizons\ItemRename;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use BlockHorizons\ItemRename\command\ItemRenameCommand;

class Loader extends PluginBase {

	public function onEnable() {
		$this->saveDefaultConfig();
		$this->getServer()->getCommandMap()->register("itemrename", new ItemRenameCommand($this));
	}
}
