(function($, sos){

	'use strict';

	function Queue()
	{

	}

	Queue.prototype = {
		add: function(prodid)
		{
			$.ajax({
				url:'ajax/etiketten.php',
				type:'POST',
				dataType:'json',
				data:
				{
					action: 'addProduct',
					prodid: prodid
				},
				success: function()
				{}
			});
		},
		remove: function(prodid)
		{
			var confirmed = window.confirm('Zeker verwijderen?');
			if(confirmed)
			{
				$.ajax({
					url:'ajax/etiketten.php',
					type:'POST',
					dataType:'json',
					data:
					{
						action:'removeProduct',
						prodid :prodid
					}
				});
			}
		}
	};

	sos.etiketten.queue = new Queue();

})(window.jQuery, window.sos);