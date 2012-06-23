<html>
<head>
<title>imager: the open-source image sharer</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery.iframe-transport.js"></script>
<script src="/assets/js/jquery.fileupload.js"></script>
<script src="/assets/js/imager.js" type="text/javascript"></script>

<link rel="stylesheet" href="/assets/css/bootstrap.css" />
<link rel="stylesheet" href="/assets/css/jquery-ui.css" />

</head>
<body>
<div class="container">
    <header>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="/">imager</a>
					<ul class="nav">
						<li class="active"><a href="/" class="primary">dashboard</a></li>
						<li class=""><a href="/gallery" class="success">browse</a></li>
						<li class=""><a href="/logout" class="info">sign out</a></li>
					</ul>					
				</div>
			</div>
		</div>
    </header>
    <div style="height: 80px;"></div>
    <div class="container">
    	<div class="span5">
    		<h2>Upload images</h2>
    		<div class="span2">
	    		<form method="post" action="#">
	    			<input type="hidden" name="upload" value="1" />
				<div style="height: 45px; width:100px; background-image: url('/assets/img/computer.jpg'); background-repeat: no-repeat; overflow:hidden;cursor: hand;">
	    				<input id="fileupload" type="file" name="files[]" class="" data-url="upload" value="Computer" style="width: 5000px; height: 45px; margin-left: -4000px;cursor:hand;" multiple>
				</div>
	    		</form>
    		</div>
    		<div class="span2">
	    		<input type="button" class="btn-inverse btn-large" value="Web" id="webget_btn" />
    		</div>
    		<div class="span6">
    			<p>or drag and drop your images onto this page</p>
    		</div>
    	</div>
    	<div class="span1">
    	</div>
    	<div class="span4">
    		<h2>View images</h2>
    		
    		<?php foreach($images as $i):?>
    			<a href="g/<?php echo $i->short_name; ?>"><img src="g/<?php echo $i->short_name; ?>" alt="<?php echo $i->original_name; ?>" style="width:80px" /></a>
    		<?php endforeach;?>
    	</div>
    </div>
    
    <div class="dialog" id="webget" title="Upload from the web">
	    <form method="post" action="webget" id="webget_form" class="well">
		<span class="help-block">Enter the URLs of images, one per line:</span>
	    	<textarea name="webget" id="webget" style="width: 250px; padding: 5px; height: 70px;"></textarea><br />
	    	<input type="submit" value="Upload" class="btn btn-primary" />
	    </form>
    </div>
</div>
</body>
</html>
