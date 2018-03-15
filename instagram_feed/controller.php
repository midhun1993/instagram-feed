<?php
namespace Concrete\Package\InstagramFeed;

use Package;
use BlockType;

class Controller extends Package{

  protected $pkgHandle = "instagram_feed";
  protected $appVersionRequired = '5.7.5.13';
  protected $pkgVersion = "0.0.1";

  public function getPackageName(){
    return t("Instagram Feed");
  }

  public function getPackageDescription(){
    return t("Addon used for get instagram public feed");
  }

  public function install(){
    $pkg = Parent::install();
    $this->installBlockType($pkg);
  }

  public function upgrade(){
    Parent::upgrade();
    $pkg = Package::getByHandle("instagram_feed");
    $this->installBlockType($pkg);
  }

  protected function installBlockType($pkg){
    $instagram_feed_blk = BlockType::getByHandle("instagram_feed");
    if(!is_object($instagram_feed_blk)){
      BlockType::installBlockTypeFromPackage("instagram_feed", $pkg);
    }
  }

  public function on_start(){
    require $this->getPackagePath().'/vendor/autoload.php';
  }

}
?>
