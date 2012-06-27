<?php echo $header; ?>

<div class="container">
	<div class="span7">
		<h2 id="caption"><?php echo $image->caption; ?></h2>
		<div>
			<a href="<?php echo $image->location; ?>/<?php echo $filename; ?>" target="_blank">
			<?php if($image->location != ''){ ?>
				<img src="<?php echo $image->location; ?>/<?php echo $filename; ?>" style="margin: 0 auto; display: block;" alt="<?php echo $image->original_name; ?>" />
			<?php } else { ?>
				<img src="<?php echo $image->location; ?>/<?php echo $filename; ?>" style="margin: 0 auto; display: block;" alt="<?php echo $image->original_name; ?>" />
			<?php } ?>
			</a>
		</div>
		<p>Contributed at <?php echo date('H:i d/m/Y',$image->created_at); ?> by <?php echo $username; ?></p>
	</div>

	<div class="span4">
		<?php echo $controls; ?>
		<h2>Tags</h2>
		<ul class="tags">
			<li>Link<br /><input type="text" readonly="readonly" value="http://img.jalder.com/v/<?php echo $image->short_name; ?>" /></li>
			<li>Direct Link<br /><input type="text" readonly="readonly" value="<?php echo $image->location; ?>/<?php echo $filename; ?>" /></li>
			<li>HTML Image<br /><input type="text" readonly="readonly" value="&lt;a href=&quot;http://img.jalder.com/v/<?php echo $image->short_name; ?>&quot;&gt;&lt;img src=&quot;<?php echo $image->location; ?>/<?php echo $filename; ?>&quot; alt=&quot;&quot; title=&quot;Hosted by imager bully&quot; /&gt;&lt;/a&gt;" /></li>
			<li>BBCode<br /><input type="text" readonly="readonly" value="[IMG]<?php echo $image->location; ?>/<?php echo $filename; ?>[/IMG]" /></li>
			<li>Linked BBCode<br /><input type="text" readonly="readonly" value="[URL=http://img.jalder.com/v/<?php echo $image->short_name; ?>][IMG]<?php echo $image->location; ?>/<?php echo $filename; ?>[/IMG][/URL]" /></li>
			<li>Markdown Link<br /><input type="text" readonly="readonly" value="[Image Link](http://img.jalder.com/v/<?php echo $image->short_name; ?>)"/></li>
		</ul>
		<p class="choose"><strong>Sizes</strong> <a href="?s=o" <?php echo ($active=='o')?'class="active"':''; ?>>Original</a> &bull; <a href="?s=s" <?php echo ($active=='s')?'class="active"':''; ?>>Small Square</a> &bull; <a href="?s=l" <?php echo ($active=='l')?'class="active"':''; ?>>Large Thumbnail</a></p>
	</div>
</div>

<?php echo $footer; ?>