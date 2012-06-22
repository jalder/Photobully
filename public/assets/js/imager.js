$(document).ready(function() {
	$('.dialog').dialog({'autoOpen': false});
	$('#upload_btn').click();
	$('#webget_btn').click(function(){
		$('#webget').dialog('open');
	});
});