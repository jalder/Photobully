<h2>Management</h2>
<ul id="controls">
	<li><a href="#" id="send_to_cloud">Send to Cloud</a></li>
	<li><a href="#" id="edit_caption">Edit Caption</a></li>
	<li><a href="/s/<?php echo $alphaID; ?>" target="_blank" id="download">Download Original</a></li>
	<li><a href="#" id="delete_image">Delete</a></li>
</ul>
<script type="text/javascript">

$(document).ready(function(){

	$('#send_to_cloud').click(function(){
		$.post('/cloud/send',{'alphaID':"<?php echo $alphaID; ?>"},function(data){
			 console.log(data); 
			 alert('Content has been moved to cloud.'); 
		},'html');
		return false;
	});

	$('#delete_image').click(function(){
		if(confirm('Delete Image?')){
			$.post('/d/<?php echo $alphaID; ?>',{'alphaID':"<?php echo $alphaID; ?>"},function(data){ 
				console.log(data); 
				if(data.error){
					alert(data.error);
				}else{ 
					window.location.href = "/"; 
			} },'json');
		}
		return false;		
	});

	$('#edit_caption').click(function(){
		var current = $('#caption').html();
		if($('#edit_caption').html()=='Edit Caption'){
			$('#caption').html('<input type="text" value="'+current+'" name="caption" id="input_caption" class="xlarge" />');
			$('#input_caption').focus();
			$('#edit_caption').html('Save Caption');
		}
		else if($('#edit_caption').html()=='Save Caption'){
			//$('#caption').html('<input type="text" value="'+current+'" name="caption" id="input_caption" class="xlarge" />');
			//$('#input_caption').focus();
			var new_caption = $('#input_caption').val();
			//do post, if success
			$.post('/u/<?php echo $alphaID; ?>',{'caption':new_caption},function(data){
				console.log(data);
				if(data.success){
					$('#caption').html(new_caption);
					$('#edit_caption').html('Edit Caption');
				}
				else{
					console.log(data);
				}
			},'json');
		}
	});
	
});
</script>