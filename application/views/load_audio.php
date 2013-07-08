<script>
  audiojs.events.ready(function() {
    var as = audiojs.createAll();
  });
</script>

<audio src="http://mmlab.cs.clemson.edu/spring13/u6/MeTube/application/uploads/audio/<?php echo $media->row()->fileName; ?>" preload="auto" />
