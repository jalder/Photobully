<?php echo $header; ?>


<div class="container">
	<div class="span7">
		<h2><?php echo $album->description; $image = ''; ?></h2>
		<?php foreach($images as $i): ?>
		<div>
			<img src="/l/<?php echo $i->short_name; ?>" style="margin: 0 auto; display: block;" alt="<?php echo $i->original_name; ?>" />
		</div>
		<?php $image = $i->short_name; endforeach; ?>
		<p>Contributed at <?php echo date('H:i d/m/Y',$album->created_at); ?> by <?php echo $username; ?></p>
	</div>

	<div class="span4">
		<h2>Tags</h2>
		<ul class="tags">
			<li>Link<br /><input type="text" readonly="readonly" value="http://img.jalder.com/a/<?php echo $album->id; ?>" /></li>
			<li>Linked BBCode<br /><input type="text" readonly="readonly" value="[URL=http://img.jalder.com/a/<?php echo $album->id; ?>][IMG]http://img.jalder.com/l/<?php echo $image; ?>[/IMG][/URL]" /></li>
			<li>Markdown Link<br /><input type="text" readonly="readonly" value="[<?php echo $album->name; ?>](http://img.jalder.com/a/<?php echo $album->id; ?>)"/></li>
		</ul>
		<p class="choose"><strong>Sizes</strong> <a href="#">Original</a> &bull; <a href="#">Small Square</a> &bull; <a class="active" href="#">Large Thumbnail</a></p>
	</div>
</div>

<?php echo $footer; ?>