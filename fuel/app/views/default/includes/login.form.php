
<form method="post" action="/account/login">
<label>Username: </label><input type="text" name="username" id="username" value="" />
<label>Password: </label><input type="password" name="password" value="" />
<input type="submit" value="Login" class="btn-inverse btn" />
</form>

<script type="text/javascript">
$(document).ready(function(){
	$('#username').focus();
	
});

</script>

