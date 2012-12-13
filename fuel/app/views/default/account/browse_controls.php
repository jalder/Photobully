<h2>Show me</h2>
<ul>
	<li><a href="#" id="public_list">Public Gallery</a></li>
	<li><a href="#" id="all_images">All My Images</a></li>
	<li><a href="#" id="hidden_images">Hidden Images</a></li>

</ul>
<h2>Tools</h2>
<ul id="tool_link">
	<li ref="albums">Albums</li>
</ul>

<div id="albums" class="tool">
	<h3>Albums</h3>
	<a href="#" id="" class="dialog_open" rel="create_album">Create</a>
	<select name="album" id="album_select" class="span2">
		<option value="#">&nbsp;</option>
		<?php foreach($albums as $a): ?>
		<option value="<?php echo $a->id; ?>"><?php echo $a->name; ?></option>
		<?php endforeach; ?>
	</select>
	<div id="drop_area" class="dropzone span2" style="height: 400px; margin-left: 2px; border: 1px dashed black;">
	
	</div>
</div>
		
<div class="dialog" title="New Album" id="create_album">
	<form method="post" action="/album/create">
		<label>Name</label><input type="text" value="" name="album_name" />
		<label>Description</label><textarea rows="" cols="" name="album_description"></textarea>
	</form>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$('.tool').hide();
	$('#tool_link li').click(function(){
		var tool = $(this).attr('ref');
		$('.tool').slideUp();
		$('#'+tool).slideDown();
	});
	
	$('#public_list').click(function(){
		$.get('/imager/list',{'type':''},function(data){
			if(data){
				build_gallery(data);
			}
		},'json');
	});
	$('#all_images').click(function(){
		$.get('/imager/list',{'type':'all'},function(data){
			if(data){
				build_gallery(data);
			}
		},'json');
	});
	$('#hidden_images').click(function(){
		$.get('/imager/list',{'type':'hidden'},function(data){
			if(data){
				build_gallery(data);
			}
		},'json');


	});
	$('.dialog').dialog({
		'autoOpen': false,
		'buttons': {
			'Create':function(){$(this).children('form').submit();},
			'Close':function(){$(this).dialog('close');}
		}
	});

	$('.dialog_open').click(function(){
		var dialog = $(this).attr('rel');
		console.log(dialog);
		$('#'+dialog).dialog('open');
	});

	$('#create_album').submit(function(){
		$.post('/album/create',$(this).children('form').serialize(),function(data){
			console.log(data[0].name);
			if( data[0].name !== undefined ){
				console.log(data[0].id);
				$('#album_select').append("<option id='"+data[0].id+"'>"+data[0].name+"</option>");
				$('#create_album').dialog('close');
				$('#album_select').val(parseInt(data[0].id));
			}
			if(data.error){
				alert(data.error);
			}
		},'json');
		return false;
	});

	$('#album_select').change(function(){
		$('#drop_area').html('');
		var album_id = $(this).val();
		$.get('/album/list/'+album_id,'',function(data){
			$.each(data.images,function(key,image){
				var image_small = image.short_name.replace('.','_s.');
				var image_add = "<img src='"+image.location+"/"+image_small+"' alt='' />";
				$('#drop_area').append(image_add);
			});
		},'json');
		
	});
	
	$(".draggable").draggable({helper:'clone'});  
	$(".dropzone").droppable({
		accept: ".draggable",
		drop: function(event,ui){
			var image = $(ui.draggable).clone();
			var album_id = $('#album_select').val();
			var alphaID = image[0].attributes[1].value.split('_');
			$.post('/album/add/'+album_id,{'alphaID':alphaID[0]},$.proxy(add_image_to_album,image),'json');
		}
	});
	
});

function add_image_to_album(response){
	var image = this;
	console.log(image[0]);
	console.log(response);
	if(response.error){
		alert(response.error);
	}
	else{
		var small = image[0];
		small.src = small.src.replace("_b","_s");
		$('.dropzone').append(small);
	}
}

function build_gallery(images){
	if(images){
		$(".draggable").draggable("destroy"); 
		$('#list').html('');
		$.each(images,function(key,image){
			$('#list').append('<div class="span2" style="margin-bottom: 20px;" ><a href="/v/'+image.short_name+'"><img src="'+image.location+'/'+image.big_thumb+'" rel="'+image.big_thumb+'" alt="" class="draggable" /></a></div>');
		});
		$(".draggable").draggable({helper:'clone'});
	}
}

</script>