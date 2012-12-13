<?php echo $header; ?>
    <div style="margin: 0 auto; padding-bottom:20px; width: 300px;">
    	<img src="http://jalder.com/assets/img/jl.jpg" alt="Jalder Labs" />
    </div>
    <div class="container">
    	<div class="span1">&nbsp;</div>
    	<div class="span5">
	<?php if(Auth::check()): ?>
    		<h2>Upload images</h2>
    		<div class="span2">
	    		<form method="post" action="#">
	    			<input type="hidden" name="upload" value="1" />
				<div class="" style="height: 45px; width:100px; background-image: url('/assets/img/computer.jpg'); background-repeat: no-repeat; overflow:hidden;cursor: hand;">
	    				<input id="fileupload" type="file" name="files[]" class="" data-url="upload" value="Computer" style="width: 5000px; height: 45px; margin-left: -4000px;cursor:hand;" multiple>
				</div>
	    		</form>
    		</div>
    		<div class="span2">
	    		<input type="button" class="btn-inverse btn-large" value="Web" id="webget_btn" />
    		</div>
    		<div class="span4">
    			<p>or drag and drop your images onto this page</p>
    		</div>
	<?php else: ?>
		<h2>Login</h2>
		<?php echo View::forge('default/includes/login.form')->render();  ?>
	<?php endif; ?>
    	</div>
    	<div class="span5">
    		<h2 style="width: 63%; display: inline-block;">View images</h2><a href="/gallery" class="btn btn-inverse" style="display: inline-block;">Browse</a>
    	
    		<?php foreach($images as $i):?>
    			<a href="v/<?php echo $i->short_name; ?>"><img src="<?php echo $i->location; ?>/<?php echo $files[$i->id]; ?>" alt="<?php echo $i->original_name; ?>" class="small_square" /></a>
    		<?php endforeach;?>
    	</div>
    	<div class=""></div>
    </div>
    
    <div class="dialog" id="webget" title="Upload from the web">
	    <form method="post" action="webget" id="webget_form" class="well">
		<span class="help-block">Enter the URLs of images, one per line:</span>
	    	<textarea name="webget" id="webget" style="width: 250px; padding: 5px; height: 70px;"></textarea><br />
	    	<input type="submit" value="Upload" class="btn btn-primary" />
	    </form>
    </div>
<?php echo $footer; ?>
