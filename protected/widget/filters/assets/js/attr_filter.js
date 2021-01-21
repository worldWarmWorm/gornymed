$(document).ready(function(){
	


	$( "#form-filter" ).on( "submit", function( event ) {
		event.preventDefault();
		var frm = $( this );
		var price_from = $('#price_from').val();
		var price_to = $('#price_to').val();
		var data = JSON.stringify(frm.serializeArray());
		$.fn.yiiListView.update(
			'ajaxListView',
			{data: {data : data, price_from : price_from, price_to : price_to  }}
		);
		
	});

});
