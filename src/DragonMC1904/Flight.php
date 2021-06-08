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
use pocketmine\math\Vector3;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("§cPlugin Enabled!");
        $this->getLogger()->info("§cPlugin Flight by DragonMC1904");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDisable(){
        $this->getLogger()->info("§cPlugin Disabled!");
        $this->getLogger()->info("§cPlugin Flight by DragonMC1904");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {
        
        switch ($cmd->getName()){
            case "flight":
             if($sender instanceof Player){			                     
                if (!$sender->isCreative()){
                    if($this->getConfig()->get("Use-Flight") === use-pers){
                      if($sender->hasPermission("flight.use")){
                        $this->openMyForm($sender);
                      } else {
                        $sender->sendMessage($this->getConfig()->get("No-Permissions"));
                        return false;
                      }
                    } else if ($this->getConfig()->get("Use-Flight") === use-money) { 
                      $economy = EconomyAPI::getInstance();
                      $money = $economy->myMoney($sender);
                      $cost = $this->getConfig()->get("Cost-Fly");
                        if($money >= $cast){
                          $economy->reduceMoney($sender, $cast);
                          $this->openMyForm($sender);
                        } else {
                          $sender->sendMessage($this->getConfig()->get("Not-Enough-Money"));  
                          return true;
                        }  
                    }
                } else {
                    $sender->sendMessage($this->getConfig()->get("Disable-Flight-In-Creative"));
                    return false;
                }            
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
        $form->setTitle("§b << Flight >> ");
            $form->setContent("§gPlease choose your choice for flight mode");
            $form->addButton("§a<< Enable Flight >>");
            $form->addButton("§c<< Disable Flight >>");
            $form->sendToPlayer($player);
            return $form;
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if($this->getConfig()->get("Disable-Flight-OnJoin") === true && !$sender->isCreative()){
            $player->sendMessage($this->getConfig->get("Message-Disable-Flight-OnJoin"));
            $player->setAllowFlight(false);
            $player->setFlying(false);
        }
    }

	public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if($this->getConfig->get("Disable-Flight-OnDamage") === true){
            if ($entity instanceof Player){
                if($event instanceof EntityDamageByEntityEvent){
                    $damager = $event->getDamager();
                    if ($damager instanceof Player){
                        if ($damager->getAllowFlight() === true){
                            if (!$damager->isCreative()){
                                $damager->sendMessage($this->getConfig()->get("Message-Disable-Flight-OnDamage"));
                                $damager->setAllowFlight(false);
                                $damager->setFlying(false);
                            }
                        }
                    }
                }
            }
        }
    }

    public function checkDepend(){
        if ($this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI") === null or $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI") === null) {
            $this->plugin->getLogger()->warning("EconomyAPI is not found! Please install it and try again.");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
            return false;
        }
        return true;
    }

}