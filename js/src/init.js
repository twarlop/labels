var sos = {
	'author': 'Thomas Warlop',
	'etiketten':{
		'version': '1.0.0'
	},
	language: function()
	{
		var lang = parseInt($('#lang').val(), 10);
		switch(lang)
		{
			case 1:
				return 'nl';
			break;
			case 2:
				return 'fr';
			break;
		}
	}
};

window.sos = sos;


(function($, sos){
	'use strict';

	$(document).ready(function(){

		$('#queueTable').on('click', '.inspect', function(){
			var prodid = parseInt($(this).closest('tr').data('prodid'), 10);
			$.inspectReborn({
				'prodid':prodid,
				'after': function(){
					sos.etiketten.queue.reload(prodid);
				}
			});
		});

	});

})(window.jQuery, window.sos);