<?php

declare(strict_types=1);

namespace BlockHorizons\ItemRename\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;

use BlockHorizons\ItemRename\Loader;

class ItemRenameCommand extends Command {
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

		$config = $this->loader->getConfig();
		$item = $sender->getInventory()->getItemInHand();

		if($item === null) {
			$sender->sendMessage($config->getNested("messages.no-item"));
			return true;
		}

		$oldName = $item->getCustomName() ?: $item->getName();
		$newName = implode(" ", $args);

		$item->clearCustomName();
		$item->setCustomName(str_replace("&", TF::ESCAPE, $newName));
		$sender->getInventory()->setItemInHand($item);

		$sender->sendMessage($this->parseMessage($config->getNested("messages.renamed"), [
			"old_name" => $oldName,
			"new_name" => $newName
		]));
		return true;
	}

	public function parseMessage(string $message, array $placeholders = []): string {
		foreach ($placeholders as $key => $value) {
			$message = str_replace("{" . $key . "}", $value, $message);
		}
		return $message;
	}
}
