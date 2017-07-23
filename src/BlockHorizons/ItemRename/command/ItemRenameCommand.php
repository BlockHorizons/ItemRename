<?php

declare(strict_types=1);

namespace BlockHorizons\ItemRename\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;

use BlockHorizons\ItemRename\Loader;

class ItemRenameCommand extends Command implements PluginIdentifiableCommand {
	/** @var Loader */
	private $loader;
	
	public function __construct(Loader $loader) {
		parent::__construct("itemrename", "Rename an item.", "/itemrename <new name>", ["irename", "irn"]);
		$this->setPermission("itemrename.rename");
		$this->loader = $loader;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player) {
			$sender->sendMessage(TF::RED . "Please run this command in-game.");
			return true;
		}
		if(empty($args)) {
			$sender->sendMessage(TF::RED . $this->getUsage());
			return true;
		}
		$item = $sender->getInventory()->getItemInHand();
		if($item === null) {
			$sender->sendMessage(TF::RED . "You are not holding an item!");
			return true;
		}
		$item->clearCustomName();
		$item->setCustomName(str_replace("&", TF::ESCAPE, $args[0]));
		$sender->sendItemInHand($item);
		if($this->getPlugin()->getConfig()->get("Display-Message", true) {
			$sender->sendMessage(vsprintf($this->getPlugin()->getConfig()->get("Message"), [
				$args[0] // new name
			]));
		}
		return true;
	}

	public function getPlugin(): Loader {
		return $this->loader;
	}
}
