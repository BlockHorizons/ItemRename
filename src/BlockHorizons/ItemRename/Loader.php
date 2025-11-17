<?php

declare(strict_types=1);

namespace BlockHorizons\ItemRename;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use BlockHorizons\ItemRename\command\ItemRenameCommand;

class Loader extends PluginBase {

	public function onEnable(): void {
		$this->saveDefaultConfig();
		$this->getServer()->getCommandMap()->register("itemrename", new ItemRenameCommand($this));
	}
}
