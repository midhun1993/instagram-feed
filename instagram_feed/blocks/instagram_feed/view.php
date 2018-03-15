<?php
foreach ($medias as $key => $media) {

  switch ($media->getType()) {
    case 'image': ?>
    <img src="<?php echo $media->getImageHighResolutionUrl(); ?>" />
    <?php
        break;

    case 'video':
      # code...
      break;
  }

  

}

?>
