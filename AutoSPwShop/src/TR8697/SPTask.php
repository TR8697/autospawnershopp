<?php

namespace TR8697;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\item\{ItemFactory, Item};

class SPTask extends Task{

    public function __construct(){
        $this->cfgsp = new Config(AutoSPMAin::getInstance()->getDataFolder()."configsp.yml", Config::YAML);
    }

    public $timer = 0;

        public function onRun(): void {
        $this->timer++;
        $kalan10 = $this->timer % 10;//for coal
        $kalan15 = $this->timer % 15;//for iron
        $kalan30 = $this->timer % 30;//for gold
        $kalan50 = $this->timer % 50;//for dia and emerald

        if ($kalan10 == 0){
            $this->cfgsp->reload();
            foreach ($this->cfgsp->getAll() as $key => $value){
                $explodedvalue = explode("-", $value);
                if ($explodedvalue[2] == "CoalSP"){
                    $level = AutoSPMain::getInstance()->getServer()->getWorldManager()->getWorldByName($explodedvalue[6]);
                    $level->dropItem(new Vector3($explodedvalue[3], $explodedvalue[4], $explodedvalue[5]), ItemFactory::getInstance()->get($explodedvalue[1], 0, 1));
                }
            }
        }
        if ($kalan15 == 0){
            foreach ($this->cfgsp->getAll() as $key => $value){
                $explodedvalue = explode("-", $value);
                if ($explodedvalue[2] == "IronSP"){
                    $level = AutoSPMain::getInstance()->getServer()->getWorldManager()->getWorldByName($explodedvalue[6]);
                    $level->dropItem(new Vector3($explodedvalue[3], $explodedvalue[4], $explodedvalue[5]), ItemFactory::getInstance()->get($explodedvalue[1], 0, 1));
                }
            }
        }
        if ($kalan30 == 0){
            foreach ($this->cfgsp->getAll() as $key => $value){
                $explodedvalue = explode("-", $value);
                if ($explodedvalue[2] == "GoldSP"){
                    $level = AutoSPMain::getInstance()->getServer()->getWorldManager()->getWorldByName($explodedvalue[6]);
                    $level->dropItem(new Vector3($explodedvalue[3], $explodedvalue[4], $explodedvalue[5]), ItemFactory::getInstance()->get($explodedvalue[1], 0, 1));
                }
            }
        }
        if ($kalan50 == 0){
            foreach ($this->cfgsp->getAll() as $key => $value){
                $explodedvalue = explode("-", $value);
                if ($explodedvalue[2] == "DiaSP"){
                    $level = AutoSPMain::getInstance()->getServer()->getWorldManager()->getWorldByName($explodedvalue[6]);
                    $level->dropItem(new Vector3($explodedvalue[3], $explodedvalue[4], $explodedvalue[5]), ItemFactory::getInstance()->get($explodedvalue[1], 0, 1));
                }
                if ($explodedvalue[2] == "EmeraldSP"){
                    $level = AutoSPMain::getInstance()->getServer()->getWorldManager()->getWorldByName($explodedvalue[6]);
                    $level->dropItem(new Vector3($explodedvalue[3], $explodedvalue[4], $explodedvalue[5]), ItemFactory::getInstance()->get($explodedvalue[1], 0, 1));
                }
            }
        }
    }
}

?>