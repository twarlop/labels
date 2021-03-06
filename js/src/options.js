(function($, sos){

	'use strict';

	$(document).ready(function(){

		$("#optionsEtiket").on('change', 'select, input', function(){
			switch($(this).attr('id'))
			{
				case 'etiketAfmeting':
					save({
						setting:'label_type2',
						value: $(this).val()
					}, function(response){
						
					});
				break;

				case 'etiketType':
					save({
						setting: 'label_mode2',
						value: $(this).val()
					}, function(response){

					});
				break;

				case 'etiketLang':
					save({
						setting: 'label_taal2',
						value: $(this).val()
					}, function(response){

					});

				break;

				case 'etiketDisclaimerNl':
					save({
						setting: 'label_disclaimer_nl',
						value: $(this).val()
					});
				break;

				case 'etiketDisclaimerFr':
					save({
						setting: 'label_disclaimer_fr',
						value: $(this).val()
					});
				break;

			}
		});


		$('#etiketDatum').datepicker({
			dateFormat: 'dd/mm/yy',
			onSelect: function(datum, datepicker)
			{
				window.location = '?datum=' + datum;
			}
        });

	});



	/**
	 * Helper function to allow us to save the settings for printing labels
	 */
	function save(data, callback)
	{
		data = $.extend({'action':'setValue'}, data);
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