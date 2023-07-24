<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

class BorderLimit extends PluginBase implements Listener
{
    public Config $config;

    public function onEnable(): void
    {
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "min_x" => -15000,
            "max_x" => 15000,
            "min_z" => -15000,
            "max_z" => 15000,
            "msg" => true,
            "msg_" => "Vous avez atteint la limite de la map !"
        ]);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $x = $event->getPlayer()->getPosition()->x;
        $z = $event->getPlayer()->getPosition()->z;

        $motion = null;
        $config = $this->config;

        if ($x <= $config->get("min_x")) {
            $motion = new Vector3(+2, 1, 0);
        } elseif ($x >= $config->get("max_x")) {
            $motion = new Vector3(-2, 1, 0);
        } elseif ($z <= $config->get("min_z")) {
            $motion = new Vector3(0, 1, +2);
        } elseif ($z >= $config->get("max_z")) $motion = new Vector3(0, 1, -2);

        if ($motion !== null) {
            $event->getPlayer()->setMotion($motion);
            if ($this->config->get("msg")) $event->getPlayer()->sendMessage($this->config->get("msg_"));
        }
    }
}
