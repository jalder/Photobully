<script src="/assets/js/jquery.Jcrop.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/css/jquery.Jcrop.min.css" type="text/css" />

<h2>Management</h2>
<?php 
	
?>
<input type="radio" name="privacy" value="0" <?php if($privacy == 0){ ?>checked="checked"<?php }?> /> Public<br />
<input type="radio" name="privacy" value="1" <?php if($privacy == 1){ ?>checked="checked"<?php }?> /> Unlisted<br />
<input type="radio" name="privacy" value="2" <?php if($privacy == 2){ ?>checked="checked"<?php }?> /> Private <br />
<ul id="controls">
	<li><a href="#" id="duplicate" rel="http://img.jalder.com/s/<?php echo $alphaID; ?>">Duplicate</a></li>
	<li><a href="#" id="send_to_cloud">Send to Cloud</a></li>
	<li><a href="#" id="edit_caption">Edit Caption</a></li>
	<li><a href="#" id="rotate_left">Rotate Left</a></li>
	<li><a href="#" id="rotate_right">Rotate Right</a></li>
	<li><a href="#" id="edit_crop">Crop</a></li>
	<li><a href="/s/<?php echo $alphaID; ?>" target="_blank" id="download">Download</a></li>
	<li><a href="#" id="delete_image">Delete</a></li>
</ul>
<script type="text/javascript">

$(document).ready(function(){
	var coord_percent = new Object();
	$('#send_to_cloud').click(function(){
		$.post('/cloud/send',{'alphaID':"<?php echo $alphaID; ?>"},function(data){
			 window.location.reload();  
		},'html');
		return false;
	});


	$('input[name="privacy"]').change(function(){
		var privacy = $(this).val();
		$.post('/u/<?php echo $alphaID; ?>',{'privacy_level':privacy},function(data){
			console.log(data);
		},'json');
	});


	$('#duplicate').click(function(){
		if(confirm('Duplicate image?')){
			$.post('/webget',{'webget':$(this).attr('rel')},function(data){
				console.log(data);
				if(data.error){
					alert(data.error);
				}
				else{
					document.location.href = '/v/'+data[0].short_name;
				}
			},'json');
		}

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

	$('#rotate_left').click(function(){
		$.post('/apply/r/<?php echo $alphaID; ?>',{'degrees':'270'},function(data){
			console.log(data);
			if(data.success){
				window.location.reload();
			}
		},'json');
	});

	$('#rotate_right').click(function(){
		$.post('/apply/r/<?php echo $alphaID; ?>',{'degrees':'90'},function(data){
			console.log(data);
			if(data.success){
				window.location.reload();
			}
		},'json');
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

	$('#caption').click(function(){
		var current = $('#caption').html();
		$('#caption').html('<input type="text" value="'+current+'" name="caption" id="input_caption" class="xlarge" />');
                $('#input_caption').focus();
                $('#edit_caption').html('Save Caption');
	});
	$('#caption #input_caption').live('blur',function(){
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
	});

	$('.single').unwrap();

	var image = $('.single');
	console.log(image);

	$('#edit_crop').live('click',function(data){
		if(coord_percent.x!==undefined){
			//console.log(JSON.stringify(coord_percent, null, 2));
			$.post('/apply/c/<?php echo $alphaID; ?>',{'coord_percent':JSON.stringify(coord_percent)},function(data){
				console.log(data);
				if(data.success){
					window.location.reload();
				}
			},'json');
		}else{
			$('.single').Jcrop({
				onChange:   function(c){setCoords(c,coord_percent);},
				onSelect:   function(c){
					$('#edit_crop').html('Save Crop');
					setCoords(c,coord_percent);
					
					}
			});
		}
	});
	
});

function setCoords(c,coord_percent){
	
	console.log(c);
	var w = $('.single')[0].width;
	var h = $('.single')[0].height;
	coord_percent.x = c.x/w*100;
	coord_percent.y = c.y/h*100;

	coord_percent.x2 = c.x2/w*100;
	coord_percent.y2 = c.y2/h*100;
	
	console.log(coord_percent);
	return coord_percent;
}
</script>
