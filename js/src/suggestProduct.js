(function($, sos){

	'use strict';

	$(document).ready(function(){

		$('#queueProduct').autocomplete({
			source: function(item, response){
				$.ajax({
					url:'ajax/etiketten2.php',
					data:{
						action:'suggestProduct',
						query: item.term
					},
					dataType:'json',
					type:'GET',
					success: function(products){
						response($.map(products, function(item) {
							return {
								'value': item.prodid,
								'label': item.name
							};
						}));
					}
				});
			},
			messages:{
				noResults: '',
				results: function(){}
			},
			minLenght: 3,
			select: function(event, ui)
			{
				sos.etiketten.queue.add(ui.item.value);
				$(this).val('');
				return false;
			},
			focus: function(){
				return false;
			}
		});

	});


})(window.jQuery, window.sos);