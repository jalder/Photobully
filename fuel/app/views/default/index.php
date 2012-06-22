<html>
<head>
<title>imager: the open-source image sharer</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery.iframe-transport.js"></script>
<script src="/assets/js/jquery.fileupload.js"></script>
<script src="/assets/js/imager.js" type="text/javascript"></script>

<link rel="stylesheet" href="/assets/css/bootstrap.css" />

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
	    			<input id="fileupload" type="file" name="files[]" class="btn" data-url="upload" value="Computer" multiple>
	    		</form>
    		</div>
    		<div class="span2">
	    		<input type="button" class="btn" value="Web" id="webget_btn" />
    		</div>
    		<div class="span6">
    			<p>or drag and drop your images onto this page</p>
    		</div>
    	</div>
    	<div class="span1">
    	</div>
    	<div class="span4">
    		<h2>View</h2>
    		
    		<?php foreach($images as $i):?>
    			<a href="g/<?php echo $i->short_name; ?>"><img src="g/<?php echo $i->short_name; ?>" alt="<?php echo $i->original_name; ?>" style="width:100px" /></a>
    		<?php endforeach;?>
    	</div>
    </div>
    
    <div class="dialog" id="webget">
	    <h2>Upload from the web</h2>
	    <form method="post" action="webget" id="webget_form">
	    	<textarea name="webget" id="webget"></textarea> Enter URLs One Per Line
	    	<input type="submit" value="Upload" />
	    </form>
    </div>
</div>
</body>
</html>