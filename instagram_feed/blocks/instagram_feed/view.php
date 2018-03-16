
<div class="grid-cover">
  <div class="grid-sizer"></div>
<?php
foreach ($medias as $key => $media) {

  //print_r(get_class_methods($media));

  switch ($media->getType()) {
    case 'image': ?>
    <div class="grid-item"><img src="<?php echo $media->getImageHighResolutionUrl(); ?>"  class="image-responsive"/> </div>
    <?php
        break;

    case 'video':?>
    <div class="grid-item"><img src="<?php echo $media->getImageHighResolutionUrl(); ?>" class="image-responsive"/> </div>
    <?php break;
  }



}

?>
</div>
<script>
$(document).ready(function(){
  $('.grid-cover').masonry({
  // set itemSelector so .grid-sizer is not used in layout
  itemSelector: '.grid-item',
  // use element for option
  columnWidth: '.grid-sizer',
  percentPosition: true
});
});
</script>
<style>
.grid-sizer, .grid-item {
    width:30%;
    float: left;
}
.grid-item  img{
  width:100%
}
</style>
