<?php

namespace TR8697;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\command\{Command, CommandSender};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\item\{ItemFactory, Item};
use pocketmine\nbt\tag\ListTag;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use form\{FormAPI, Form, CustomForm, ModalForm, SimpleForm};
use onebone\economyapi\EconomyAPI;

class AutoSPMain extends PluginBase implements Listener{

    private static $i;


    public function onLoad():void
    {
        self::$i = $this;
    }

    public static function getInstance() :AutoSPMain
    {
        return self::$i;
    }

    public function onEnable(): void {
        $this->cfgsp = new Config($this->getDataFolder()."configsp.yml", Config::YAML);
        $this->getLogger()->info("§aEkleni Aktif");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new SPTask(), 20);
    }

    public function onJoin(PlayerJoinEvent $event){
        $g = $event->getPlayer();
        $cfgp = new Config($this->getDataFolder()."configp.yml", Config::YAML);
    }

    public function onCommand(CommandSender $g, Command $cmd, string $label, array $args): bool {
        $cfgp = new Config($this->getDataFolder()."configp.yml", Config::YAML);
        if ($cmd->getName() == "autospadmin"){
            if (!empty($args[0])){
                if (!empty($args[3])){
                    if ($args[0] == "spver"){
                        if (Server::getInstance()->isOp($g->getName())){
                            $this->GiveSP($args[1], $args[2], $args[3]);
                        }else{
                            $g->sendMessage("Bunun için yetkiniz yok");
                        }
                    }
                }
            }
        }
        if ($cmd->getName() == "spmarket"){
            $this->SPMarketForm($g);
        }
        return true;
    }

    public function onPlace(BlockPlaceEvent $event){
        $item = $event->getItem();
        $block = $event->getBlock();
        $g = $event->getPlayer();
        $cfgsp = new Config($this->getDataFolder()."configsp.yml", Config::YAML);
        if ($item->getId() == 16 OR $item->getId() == 15 OR $item->getId() == 56 OR $item->getId() == 129 OR $item->getId() == 14){
            if ($item->getId() == 16){//kömür
                if ($item->getCustomName() == "§bOtomatik Kömür Spawner"){
                    $cfgsp->set($block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName(), "".$g->getName()."-263-CoalSP-".$block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName());
                    $cfgsp->save();
                    $g->sendPopup("§aSpawner Başarıyla Kuruldu");
                }
            }elseif($item->getId() == 15){//demir
                if ($item->getCustomName() == "§bOtomatik Demir Spawner"){
                    $cfgsp->set($block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName(), "".$g->getName()."-265-IronSP-".$block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName());
                    $cfgsp->save();
                    $g->sendPopup("§aSpawner Başarıyla Kuruldu");
                }
            }elseif($item->getId() == 56){//elmas
                if ($item->getCustomName() == "§bOtomatik Elmas Spawner"){
                    $cfgsp->set($block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName(), "".$g->getName()."-264-DiaSP-".$block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName());
                    $cfgsp->save();
                    $g->sendPopup("§aSpawner Başarıyla Kuruldu");
                }
            }elseif($item->getId() == 129){//emerald
                if ($item->getCustomName() == "§bOtomatik Zümrüt Spawner"){
                    $cfgsp->set($block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName(), "".$g->getName()."-388-EmeraldSP-".$block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName());
                    $cfgsp->save();
                    $g->sendPopup("§aSpawner Başarıyla Kuruldu");
                }
            }elseif($item->getId() == 14){//gold
                if ($item->getCustomName() == "§bOtomatik Altın Spawner"){
                    $cfgsp->set($block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName(), "".$g->getName()."-266-GoldSP-".$block->getPosition()->getX()."-".$block->getPosition()->getY()."-".$block->getPosition()->getZ()."-".$g->getWorld()->getFolderName());
                    $cfgsp->save();
                    $g->sendPopup("§aSpawner Başarıyla Kuruldu");
                }
            }
        }
    }

    public function onBreak(BlockBreakEvent $event){
        $g = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();
        $level = $g->getWorld();
        $cfgsp = new Config($this->getDataFolder()."configsp.yml", Config::YAML);
        if ($cfgsp->get($block->getPosition()->getX() . "-" . $block->getPosition()->getY() . "-" . $block->getPosition()->getZ()."-".$g->getWorld()->getFolderName())) {
            $spcfgsi = explode("-", $cfgsp->get($block->getPosition()->getX() . "-" . $block->getPosition()->getY() . "-" . $block->getPosition()->getZ()."-".$g->getWorld()->getFolderName()));
            if ($spcfgsi[0] == $g->getName()){
                $this->GiveSP($g->getName(), $spcfgsi[2], 1);
                $cfgsp->remove($block->getPosition()->getX() . "-" . $block->getPosition()->getY() . "-" . $block->getPosition()->getZ()."-".$g->getWorld()->getFolderName());
                $cfgsp->save();
            }else{
                $event->cancel();
                $g->sendMessage("Başkasının Spawnerını Kıramazsın");
            }
        }

    }

    public function GiveSP(string $gname, $typeSP, int $count){
        $g = Server::getInstance()->getPlayerByPrefix($gname);

        $coalsp = ItemFactory::getInstance()->get(16,0,$count);
        $coalsp->setCustomName("§bOtomatik Kömür Spawner");
        $coalsp->getNamedTag()->setTag(Item::TAG_ENCH,  new ListTag([]));
        $coalsp->setLore([0 => "Spawnerı Aktif Etmek için Yere Koy"]);

        $ironsp = ItemFactory::getInstance()->get(15,0,$count);
        $ironsp->setCustomName("§bOtomatik Demir Spawner");
        $ironsp->getNamedTag()->setTag(Item::TAG_ENCH,  new ListTag([]));
        $ironsp->setLore([0 => "Spawnerı Aktif Etmek için Yere Koy"]);

        $diasp = ItemFactory::getInstance()->get(56,0,$count);
        $diasp->setCustomName("§bOtomatik Elmas Spawner");
        $diasp->getNamedTag()->setTag(Item::TAG_ENCH,  new ListTag([]));
        $diasp->setLore([0 => "Spawnerı Aktif Etmek için Yere Koy"]);

        $emeraldsp = ItemFactory::getInstance()->get(129,0,$count);
        $emeraldsp->setCustomName("§bOtomatik Zümrüt Spawner");
        $emeraldsp->getNamedTag()->setTag(Item::TAG_ENCH,  new ListTag([]));
        $emeraldsp->setLore([0 => "Spawnerı Aktif Etmek için Yere Koy"]);

        $goldsp = ItemFactory::getInstance()->get(14,0,$count);
        $goldsp->setCustomName("§bOtomatik Altın Spawner");
        $goldsp->getNamedTag()->setTag(Item::TAG_ENCH,  new ListTag([]));
        $goldsp->setLore([0 => "Spawnerı Aktif Etmek için Yere Koy"]);

        if ($typeSP == "CoalSP" or $typeSP == "IronSP" or $typeSP == "DiaSP" or $typeSP == "EmeraldSP" or $typeSP == "GoldSP"){
            if ($typeSP == "CoalSP"){
                $g->getInventory()->addItem($coalsp);
            }elseif ($typeSP == "IronSP"){
                $g->getInventory()->addItem($ironsp);
            }elseif ($typeSP == "DiaSP"){
                $g->getInventory()->addItem($diasp);
            }elseif ($typeSP == "EmeraldSP"){
                $g->getInventory()->addItem($emeraldsp);
            }elseif ($typeSP == "GoldSP"){
                $g->getInventory()->addItem($goldsp);
            }
        }
    }

    public function SPMarketForm(Player $g){
        $form = new SimpleForm(function(Player $g, $data = null){
            if($data === null){
                return true;
            }
            switch($data) {
                case 0:
                        if (EconomyAPI::getInstance()->myMoney($g) >= 200000){
                            $this->GiveSP($g->getName(), "CoalSP", 1);
                            EconomyAPI::getInstance()->reduceMoney($g, 200000);
                            $g->sendMessage("§aBaşarıyla 200K Karşılığında Otomatik KömürSP Aldın");
                        }else{
                            $g->sendMessage("§cYeterli Paranız Olmadığı İçin İşlem Başarısız");
                        }
                    break;    
                case 1:
                        if (EconomyAPI::getInstance()->myMoney($g) >= 400000){
                            $this->GiveSP($g->getName(), "IronSP", 1);
                            EconomyAPI::getInstance()->reduceMoney($g, 400000);
                            $g->sendMessage("§aBaşarıyla 200K Karşılığında Otomatik DemirSP Aldın");
                        }else{
                            $g->sendMessage("§cYeterli Paranız Olmadığı İçin İşlem Başarısız");
                        }
                    break;  
                case 2:
                        if (EconomyAPI::getInstance()->myMoney($g) >= 600000){
                            $this->GiveSP($g->getName(), "DiaSP", 1);
                            EconomyAPI::getInstance()->reduceMoney($g, 600000);
                            $g->sendMessage("§aBaşarıyla 200K Karşılığında Otomatik ElmasSP Aldın");
                        }else{
                            $g->sendMessage("§cYeterli Paranız Olmadığı İçin İşlem Başarısız");
                        }
                    break;  
                case 3:
                        if (EconomyAPI::getInstance()->myMoney($g) >= 800000){
                            $this->GiveSP($g->getName(), "GoldSP", 1);
                            EconomyAPI::getInstance()->reduceMoney($g, 800000);
                            $g->sendMessage("§aBaşarıyla 200K Karşılığında Otomatik AltınSP Aldın");
                        }else{
                            $g->sendMessage("§cYeterli Paranız Olmadığı İçin İşlem Başarısız");
                        }
                    break;  
                case 4:
                        if (EconomyAPI::getInstance()->myMoney($g) >= 1000000){
                            $this->GiveSP($g->getName(), "EmeraldSP", 1);
                            EconomyAPI::getInstance()->reduceMoney($g, 1000000);
                            $g->sendMessage("§aBaşarıyla 200K Karşılığında Otomatik ZümrütSP Aldın");
                        }else{
                            $g->sendMessage("§cYeterli Paranız Olmadığı İçin İşlem Başarısız");
                        }
                    break;
                                                    
            }
        });
        $form->setTitle("§6OtoSP Market Menüsü");
        $form->addButton("KömürSP - 200K");
        $form->addButton("DemirSP - 400K");
        $form->addButton("ElmasSP - 600K");
        $form->addButton("AltınSP - 800K");
        $form->addButton("Zümrüt - 1M");
        $form->sendToPlayer($g);
    }
}
?>