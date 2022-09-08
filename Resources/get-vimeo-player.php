<?php
$getVimeoPlayerText = function($vimeoCode)
{
  echo "
<div class='sub-heading-content' data-bind='html: subheading.content'><style>
.videoWrapper {
  position: relative;
  padding-bottom: 56.25%; /* 16:9 */
  height: 0;
}
.videoWrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
</style>
<div class='videoWrapper'>
<iframe src='https://player.vimeo.com/video/{$vimeoCode}' width='640' height='360' frameborder='0' allow='autoplay; fullscreen' allowfullscreen=''></iframe>
</div>
</div>
  ";
}
?>
