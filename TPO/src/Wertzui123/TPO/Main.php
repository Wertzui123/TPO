<?php

declare(strict_types=1);

namespace Wertzui123\TPO;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Wertzui123\TPO\commands\tpo;

class Main extends PluginBase {

    public $cfgversion = 1.0;
    private $msgs;

    public function onEnable()
    {
        $this->ConfigUpdater($this->cfgversion);
        $this->msgs = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
        $data = ["command" => $this->getConfig()->get("command"), "desc" => $this->getConfig()->get("description"), "aliases" => $this->getConfig()->get("aliases")];
		$this->getServer()->getCommandMap()->register("TPO", new tpo($this, $data));
    }

    public function getMSGS() : Config{
        return $this->msgs;
    }

    public function ConfigUpdater($version)
    {
        $cfgpath = $this->getDataFolder() . "config.yml";
        $msgpath = $this->getDataFolder() . "messages.yml";
        if (file_exists($cfgpath)) {
            $cfgversion = $this->getConfig()->get("version");
            if ($cfgversion !== $version) {
                $this->getLogger()->info("Your config has been renamed to config-" . $cfgversion . ".yml and your messages file has been renamed to messages-" . $cfgversion . ".yml. That's because your config version wasn't the latest avable. So we created a new config and a new messages file for you!");
                rename($cfgpath, $this->getDataFolder() . "config-" . $cfgversion . ".yml");
                rename($msgpath, $this->getDataFolder() . "messages-" . $cfgversion . ".yml");
                $this->saveResource("config.yml");
                $this->saveResource("messages.yml");
            }
        } else {
            $this->saveResource("config.yml");
            $this->saveResource("messages.yml");
        }
    }

}
