<?php
namespace Concrete\Package\InstagramFeed\Src;

use Concrete\Package\InstagramFeed\Src\Instagram\Instagram;

class InstagramService
{

  protected $username;

  function __construct($user_name = false)
  {
      if($user_name){
        $this->setUserName($user_name);
      }

  }

  public function setUserName($user_name){
    $this->username = $user_name;
  }

  public function getPublicMediaFiles(){
    $instagram = new Instagram();
    $medias = $instagram->getMedias($this->username);
    return $medias;
  }


}
