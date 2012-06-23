$(document).ready(function() {
	$('.dialog').dialog({
		'autoOpen': false,
		'modal':true,
		'width':330,
		'height':260
	});
	$('#upload_btn').click();
	$('#webget_btn').click(function(){
		$('#webget').dialog('open');
	});
	
	$('#webget_form').submit(function(){
		$.post('webget',$(this).serialize(),function(data){
			console.log(data);
			$('#webget').dialog('close');
		},'json');
		return false;
	});

	$('#computer_btn').click(function(){
		$('#fileupload').select();
	});
	
	$('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
	
});
