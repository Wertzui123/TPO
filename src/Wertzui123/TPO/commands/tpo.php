<?php

declare(strict_types=1);

namespace Wertzui123\TPO\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\level\Position;
use pocketmine\Player;
use Wertzui123\TPO\Main;

class tpo extends Command
{

    public $plugin;

    public function __construct(Main $plugin, $data)
    {
        parent::__construct($data["command"], $data["desc"], null, $data["aliases"]);
        $this->setPermission("tpo.cmd.tpo");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {

        $cfg = $this->plugin->getConfig()->getAll();
        $msgs = $this->plugin->getMSGS()->getAll();

        if(!$sender instanceof Player){
            $sender->sendMessage($msgs["run_ingame"]);
            return true;
        }

		if(!$sender->hasPermission($this->getPermission())){
		    $sender->sendMessage($msgs["no_permission"]);
		    return true;
        }
		if(!isset($args[0])){
		    $sender->sendMessage($cfg["usage"]);
		    return true;
        }
		if(is_numeric($args[0])){
		    if(isset($args[1]) && isset($args[2])){
		        if(is_numeric($args[2]) && is_numeric($args[2])) {
                    $sender->teleport(new Position(floatval($args[0]), floatval($args[1]), floatval($args[2]), $sender->getLevel()));
                    $sender->sendMessage(str_replace(["{x}", "{y}", "{z}"], [$args[0], $args[1], $args[2]], $msgs["teleport_succes"]));
                }else{
                    $sender->sendMessage($cfg["usage"]);
                }
                }else{
                $sender->sendMessage($cfg["usage"]);
            }
        }else{
		    if(($player = $this->plugin->getServer()->getPlayer(implode(" ", $args)))){
		        $sender->teleport($player);
		        $sender->sendMessage(str_replace("{player}", $player->getName(), $msgs["teleport_succes_player"]));
            }else{
		        $sender->sendMessage(str_replace("{name}", implode(" ", $args), $msgs["no_player_found"]));
            }
        }
		return true;
    }
}