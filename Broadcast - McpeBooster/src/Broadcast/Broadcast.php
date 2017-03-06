<?php

namespace Broadcast;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\Config;

class Broadcast extends PluginBase implements Listener{

  public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->initConfig();
    
    $config = new Config($this->getServer()->getDataPath()."/plugins/Broadcast/broadcast.yml");
    $repeating = $config->get("Repeating in Sekunden") * 20;
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new BroadcastTask($this),$repeating);
    $this->getLogger()->info($config->get("Prefix")." by McpeBooster!");
  }
  
  public function initConfig(){
    @mkdir($this->getServer()->getDataPath()."/plugins/Broadcast");
    $config = new Config($this->getServer()->getDataPath()."/plugins/Broadcast/broadcast.yml");
    if(!$config->exists("Prefix")){
      $config->set("Prefix", "§2[§1Server§2]");
      $config->set("Repeating in Sekunden", "60");
      $config->set("Broadcast", ["Booster ist cool","Mit Booster geht es dir besser","Twitter: @McpeBooster","YouTube: McpeBooster","GitHub: McpeBooster"]);
      $config->save();
    }
  }
}

class BroadcastTask extends PluginTask{

  public $allBroadcast = array();
  
  public function __construct($plugin){
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }
  
  public function onRun($tick){
    $config = new Config($this->plugin->getDataFolder()."broadcast.yml");
    $allBroadcast = $config->get("Broadcast");
    
    $broadcast = array_rand($allBroadcast);
    $this->plugin->getServer()->broadcastMessage($config->get("Prefix")." ".$allBroadcast[$broadcast]);
  }
}