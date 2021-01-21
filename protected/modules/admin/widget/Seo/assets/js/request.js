


$(document).ready(function(){
	$('#generateSitemap').on('click', function(){
		$.ajax({
		  method: "POST",
		  url: "/cp/ajax/generateMap",
		})
		.done(function( data ) {
		    $('#savedXml').show(200).delay(1000).fadeOut(1000);
		});
	});	
});

