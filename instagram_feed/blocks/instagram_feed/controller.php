<?php
namespace Concrete\Package\InstagramFeed\Block\InstagramFeed;

use \Concrete\Core\Block\BlockController;
use Concrete\Package\InstagramFeed\Src\InstagramService;

class Controller extends BlockController
{


    protected $btInterfaceWidth = "450";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "538";


	  public function getBlockTypeDescription()
    {
        return t("fetch feed from instagram");
    }

    public function getBlockTypeName()
    {
        return t("Instagram Feed");
    }

    public function view(){
      $service = new InstagramService("concrete5");
      $medias = $service->getPublicMediaFiles();
      $this->set('medias', $medias);

    }

}

 ?>
