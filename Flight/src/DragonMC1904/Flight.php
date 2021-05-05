<?php

namespace Flight;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use pocketmine\utils\TextFormat;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

class main extends PluginBase implements Listener {
    
    public function onEnable(){
        
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {
        
        switch ($cmd->getName()){
            case "flight":
             if($sender instanceof Player){
                if($sender->hasPermission("flight.use")){
                    $this->openMyForm($sender);
                } else {
                    $sender->sendMessage($this->getConfig()->get("No-Permissions"));
                }
             }
        }
    return true;    
    }
    
    public function openMyForm($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:
                    $player->setAllowFlight(true);
                    $player->setFlying(true);
                    $player->sendMessage($this->getConfig()->get("Enable-Flight"));
                break;
                
                case 1:
                    $player->setAllowFlight(false);
                    $player->setFlying(false);
                    $player->sendMessage($this->getConfig()->get("Disable-Flight"));
                break;    
            }
        });
        $form->setTitle("§b << Flight >>");
            $form->setContent("§gPlease choose your choice for flight mode");
            $form->addButton("§a<< Enable Flight >>");
            $form->addButton("§c<< Disable Flight >>");
            $form->sendToPlayer($player);
            return $form;
    }
	
    	private function checkUpdate(){
		try {
			checkUpdate = new GitHubBuildsRelease(plugin, file, "DragonMC1904/Flight/release");
			if(!isset($info["status"]) or $info["status"] !== true){
				$this->getLogger()->notice("Something went wrong on update server.");
				return false;
			}
			if($info["Update-Available"] === true){
				$this->getLogger()->notice("New version: (".$info["new-version"].") of Flight is out. Check it at ".$info["download-address"]);
			}
			$this->getLogger()->notice($info["notice"]);
			return true;
		}catch(\Throwable $e){
			$this->getLogger()->logException($e);
			return false;
		}
	}
}