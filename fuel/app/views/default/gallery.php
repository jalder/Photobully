<?php echo $header; ?>
<div class="container">
	<div class="row">
		Filters: Sort By<select class="input-small"><option>Newest</option></select>
	
	</div>
	<div class="span9" style="margin-left: 0px;" id="list">
		<?php foreach($images as $i): ?>
			<div class="span2" style="margin-bottom: 20px;">
			<?php ?>
				<a href="<?php echo $lightbox[$i->id]; ?>" rel="slideshow" class="fancybox" title="<?php echo $i->caption; ?> &lt;a href='/v/<?php echo $i->short_name; ?>'&gt;view&lt;/a&gt;"><img src="<?php echo $i->location; ?>/<?php echo $files[$i->id]; ?>" rel="<?php echo $files[$i->id]; ?>" class="draggable" alt="" /></a>
			</div>
		<?php endforeach;?>
	</div>
	<div class="span3">
		<?php echo $sidebar; ?>
	</div>
</div>



<script type="text/javascript">
<!--

$(document).ready(function(){




	$('.fancybox').fancybox();
	
});

//-->
</script>

<?php echo $footer; ?>