<?php $form = Core::make("helper/form");  ?>
<div class="form-group">
  <?php echo $form->label("instagramUsername",t("Instagram Username")) ?>
  <?php echo  $form->text("instagramUsername",$instagramUsername) ?>
</div>
