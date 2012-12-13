<?php echo $header; ?>
<div class="row">
<div class="well span4">
	<h2>Account</h2>
	<form method="post" action="/account/settings" class="form-horizontal">
		<label>Email: </label><input type="text" value="<?php echo $user->email; ?>" name="email" />
		
		<br /><a href="##" id="show_pwchange">Change Password</a><br />
		<?php echo $msg; ?>
		<div id="pwchange">
			<label>Original Password: </label><input type="password" value="" name="o_password" />
			<label>New Password: </label><input type="password" value="" name="c_password" />
			<label>Repeat Password: </label><input type="password" value="" name="r_password" />
		</div>
		<input type="submit" value="Save" class="btn btn-inverse" />
	</form>
</div>

<div class="well span4">
<h2>API Configuration</h2>
<label>API Key: </label><input disabled="disabled" type="text" class="span4" value="<?php echo $user->api_key?>" name="api_key" id="api_key" /> <br /><input type="button" class="btn btn-inverse" value="Generate" id="gen_key" />
</div>
</div>

<div class="row">
<div class="well span4">
<h2>Remote CDN Support</h2>
<strong>Rackspace</strong>
<form method="post" action="/account/settings" class="form-horizontal">
<label>Username: </label><input type="text" name="rackspace_user" value="<?php echo $rackspace_username; ?>" />
<label>API Key: </label><input type="password" name="rackspace_api_key" value="<?php echo $rackspace_api_key; ?>" /><br />
<input type="submit" value="Save" class="btn btn-inverse" />
</form>
</div>

<div class="well span4">
<h2>Albums</h2>
<ul>
	<?php foreach($albums as $a): ?>
	<li><a href="/a/<?php echo $a->id?>"><?php echo $a->name; ?></a> <a href="#" class="delete_album" ref="<?php echo $a->id; ?>">delete</a></li>
	<?php endforeach; ?>
</ul>
</div>

</div>
<script type="text/javascript">

$(document).ready(function(){

	$('.delete_album').click(function(){
		var album_id = $(this).attr('ref');
		var li = $(this).parent();
		if(confirm('delete album '+album_id+'?')){
			$.post('/album/delete/'+album_id,{'album_id':album_id},function(data){
				//console.log(data);
				if(data.success){
					li.remove();
				}
				else{
					alert(data.error);
				}
					
			},'json');
		}

		return false;
	});

	$('#pwchange').hide();
	$('#show_pwchange').click(function(){
		$('#pwchange').slideDown();
		return false;
	});

	$('#gen_key').click(function(){
		$.post('/user/generate/key',{},function(data){
			console.log(data);
			if(data.api_key){
				$('#api_key').val(data.api_key);
			}
		},'json');
		return false;
	});

	
});

</script>

<?php echo $footer; ?>