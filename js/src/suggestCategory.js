(function($, sos){

	'use strict';

	$(document).ready(function(){

		$('#etiketCategorieSearch').autocomplete({
			source: function(item, response){
				suggest(item, response);
			},
			messages:{
				noResults: '',
				results: function(){}
			},
			minLenght: 2,
			select: function(event, ui)
			{
				sos.etiketten.categoryInspect(ui.item.value);
				$(this).val('');
				return false;
			},
			focus: function(){
				return false;
			}
		});

		function suggest(item, response)
		{
			$.ajax({
				url:'ajax/etiketten.php',
				data:{
					action:'suggestCategory',
					query: item.term
				},
				dataType:'json',
				type:'GET',
				success: function(categories){
					response(categories);
				}
			});
		}

		$("#queueTable").on('click', '.inspectCategory', function()
		{
			var categoryId = $(this).data('category-id');
			sos.etiketten.categoryInspect(categoryId);
		});

	});

})(window.jQuery, window.sos);