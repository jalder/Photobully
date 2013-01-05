<?php echo $header; ?>
<div class="container">
	<div class="row">
		<div class="well span12">
			&nbsp;
		
		
		</div>
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
		<div class="well">
		<?php echo $sidebar; ?>
		</div>
		<?php //include('account/trash_can.php'); ?>
		<div class="well">
			<h3>Public Albums</h3>
			<ul>
				<?php foreach($public_albums as $a): ?>
					<li><a href="/a/<?php echo $a->id; ?>"><?php echo $a->name; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
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
