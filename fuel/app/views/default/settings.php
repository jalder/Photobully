<?php echo $header; ?>

<h2>Account</h2>
<form method="post" action="/account/settings">
<label>Email: </label><input type="text" value="<?php echo $user->email; ?>" name="email" />
<h3>Change Password</h3>
<?php echo $msg; ?>
<label>Original Password: </label><input type="password" value="" name="o_password" />
<label>New Password: </label><input type="password" value="" name="c_password" />
<label>Repeat Password: </label><input type="password" value="" name="r_password" />
<input type="submit" value="Save" />
</form>
<h2>CDN Support</h2>
<strong>Rackspace</strong>
<form method="post" action="/account/settings">
<label>Username: </label><input type="text" name="rackspace_user" value="<?php echo $rackspace_username; ?>" />
<label>API Key: </label><input type="password" name="rackspace_api_key" value="<?php echo $rackspace_api_key; ?>" />
<input type="submit" value="Save" />
</form>

<h2>Albums</h2>
<ul>
	<?php foreach($albums as $a): ?>
	<li><a href="/a/<?php echo $a->id?>"><?php echo $a->name; ?></a></li>
	<?php endforeach; ?>
</ul>

<?php echo $footer; ?>