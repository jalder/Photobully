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
		spinner.spin();

		$.post('webget',$(this).serialize(),function(data){
			console.log(data);
			if(data.error){
				alert(data.error);
			}
			else{
				$('#webget').dialog('close');
				document.location.href = '/v/'+data[0].short_name;
			}
			spinner.stop();
		},'json');
		return false;
	});

	$('#computer_btn').click(function(){
		$('#fileupload').select();
	});
	var target = document.getElementById('main');
	var spinner = new Spinner(opts).spin(target);
	spinner.stop();
	$('#fileupload').fileupload({
        dataType: 'json',
        drop: function(e, data) {
        	spinner.spin()
    		console.log(data);
    		return true;
        },
        done: function (e, data) {
            console.log(data);
            spinner.stop();
            document.location.href = '/gallery';
        }
    });
	
});


var opts = {
		  lines: 13, // The number of lines to draw
		  length: 7, // The length of each line
		  width: 4, // The line thickness
		  radius: 10, // The radius of the inner circle
		  rotate: 0, // The rotation offset
		  color: '#000', // #rgb or #rrggbb
		  speed: 1, // Rounds per second
		  trail: 60, // Afterglow percentage
		  shadow: false, // Whether to render a shadow
		  hwaccel: false, // Whether to use hardware acceleration
		  className: 'spinner', // The CSS class to assign to the spinner
		  zIndex: 2e9, // The z-index (defaults to 2000000000)
		  top: 'auto', // Top position relative to parent in px
		  left: 'auto' // Left position relative to parent in px
		};
		
		
