(function($, sos){

	'use strict';

	$(document).ready(function(){

		$("#optionsEtiket").on('change', 'select', function(){
			switch($(this).attr('id'))
			{
				case 'etiketAfmeting':
					save({
						setting:'label_type',
						value: $(this).val()
					}, function(response){
						
					});
				break;

				case 'etiketType':
					save({
						setting: 'label_mode',
						value: $(this).val()
					}, function(response){

					});
				break;

				case 'etiketLang':
					save({
						setting: 'label_taal',
						value: $(this).val()
					}, function(response){

					});

				break;

			}
		});

	});

	/**
	 * Helper function to allow us to save the settings for printing labels
	 */
	function save(data, callback)
	{
		data = $.extend({'action':'setValue'}, data);
		console.log(data);
		$.ajax({
			url: 'ajax/settings.php',
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function (response)
			{
				//callback could be to reload queueTable
				if(typeof callback === 'function')
				{
					callback(response);
				}
			}
		})
	}

})(window.jQuery, window.sos);