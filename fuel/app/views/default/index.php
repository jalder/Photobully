<html>
<head>
<title>Imager</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/js/imager.js" type="text/javascript"></script>

<link rel="stylesheet" href="/assets/css/bootstrap.css" />

</head>
<body>
<div class="container">
    <header>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="/">Imager</a>
					<ul class="nav">
						<li class="active"><a href="/" class="primary">Dashboard</a></li>
						<li class=""><a href="/browse" class="success">Browse</a></li>
						<li class=""><a href="/logout" class="info">Sign Out</a></li>
					</ul>					
				</div>
			</div>
		</div>
    </header>
    
    <div class="container">
    	<div class="span4">
    		<h2>Upload images</h2>
    		<div class="span2">
	    		<form method="post" action="#">
	    			<input type="hidden" name="upload" value="1" />
	    			<input type="button" value="Computer" id="upload_btn" />
	    		</form>
    		</div>
    		<div class="span2">
	    		<form method="post" action="#">
	    			<input type="button" value="Web" id="webget_btn" />
	    		</form>
    		</div>
    		<div class="span3">
    			<p>or drag and drop your images onto this page</p>
    		</div>
    	</div>
    	<div class="span1">
    	</div>
    	<div class="span4">
    		<h2>View</h2>
    	</div>
    </div>
    
    <div class="dialog" id="webget">
	    <h2>Upload from the web</h2>
	    <form method="post" action="#">
	    	<textarea name="webget" id="webget"></textarea> Enter URLs One Per Line
	    	<input type="submit" value="Upload" />
	    </form>
    </div>
</div>
</body>
</html>