<html>
<head>
<link rel="shortcut icon" href="/favicon.ico?v=2" type="image/x-icon" />
<title>photobully: the open-source image sharer</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery.iframe-transport.js"></script>
<script src="/assets/js/jquery.fileupload.js"></script>
<script src="/assets/js/spinner.js"></script>
<script src="/assets/js/imager.js" type="text/javascript"></script>

<link rel="stylesheet" href="/assets/css/bootstrap.css" />
<link rel="stylesheet" href="/assets/css/jquery-ui.css" />
<link rel="stylesheet" href="/assets/css/imager.css" />
</head>
<body>
<div class="container" id="main">
    <header>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="/">photobully</a>
					<ul class="nav">
						<li class="<?php if($active == "dashboard"){ echo "active"; }?>"><a href="/" class="primary">dashboard</a></li>
						<li class="<?php if($active == "gallery"){ echo "active"; }?>"><a href="/gallery" class="success">browse</a></li>
						<li class="<?php if($active == "api"){ echo "active"; }?>"><a href="/documentation">api</a></li>
						<li class=""><a href="http://jalder.com/software/photobully" target="_blank">source code</a></li>
						<?php if(Auth::check()):?>
						<li class="<?php if($active == "settings"){ echo "active"; }?>"><a href="/account/settings">account settings</a></li>
						<li class=""><a href="/account/logout" class="info">sign out</a></li>
						<?php else: ?>
						<li class="<?php if($active == "login"){ echo "active"; }?>"><a href="/account/login" class="info">sign in</a></li>
						<?php endif;?>
					</ul>					
				</div>
			</div>
		</div>
    </header>
    <div style="height: 50px;"></div>