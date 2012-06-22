$(document).ready(function() {
	$('.dialog').dialog({'autoOpen': false});
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
	
	$('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
	
});