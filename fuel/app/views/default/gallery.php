<?php echo $header; ?>
<div class="container">
	<div class="span9" style="margin-left: 0px;">
		<?php foreach($images as $i): ?>
			<div class="span2" style="margin-bottom: 20px;">
				<a href="/v/<?php echo $i->short_name; ?>"><img src="<?php echo $i->location; ?>/<?php echo $files[$i->id]; ?>" alt="" /></a>
			</div>
		<?php endforeach;?>
	</div>
	<div class="span2">
		<h2>Tools</h2>
		<ul>
			<li>Albums</li>
		</ul>
		
		<div id="albums" class="tool">
			<h3>Albums</h3>
			<div id="drop_area" style="height: 400px;">
			
			</div>
			<a href="#" id="create_album">Create</a>
			<select name="album">
				<option value="1">Test</option>
			</select>
		</div>
		
	</div>
</div>

<?php echo $footer; ?>