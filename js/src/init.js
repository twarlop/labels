var sos = {
	'author': 'Thomas Warlop',
	'etiketten':{
		'version': '1.0.0'
	}
};

window.sos = sos;


(function(){
	'use strict';

	$(document).ready(function(){

		$("#queueTable").on('click', '.inspect', function(){
			var prodid = parseInt($(this).closest('tr').data('product'), 10);
			$.inspectReborn({
				'prodid':prodid
			});
		});

	})

})(window.jQuery, window.sos);