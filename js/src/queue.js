(function($, sos){

	'use strict';

	function Queue()
	{

	}

	Queue.prototype = {
		add: function(prodid)
		{
			var that = this;
			$.ajax({
				url:'ajax/etiketten.php',
				type:'POST',
				dataType:'json',
				data:
				{
					action: 'addProduct',
					prodid: prodid
				},
				success: function(product)
				{
					that.addRow(product);
				}
			});
		},
		delete: function(tr)
		{
			var prodid = tr.data('prodid');
			var confirmed = window.confirm('Zeker verwijderen?');
			if(confirmed)
			{
				$.ajax({
					url:'ajax/etiketten.php',
					type:'POST',
					dataType:'json',
					data:
					{
						action: 'removeProduct',
						prodid: prodid
					},
					success: function(){
						tr.remove();
					}
				});
			}
		},
		addRow: function(product){
			var tr =  $('<tr/>', {
				'data-prodid': product.product_id,
			});
			this.addFoto(tr, product)
				.addArtikelInfo(tr, product)
				.addMerk(tr, product)
				.addPrijs(tr, product)
				.addPromotie(tr, product)
				.addCustomLabel(tr, product)
				.addActions(tr);
			$('#queueTable tbody').append(tr);
		},
		addFoto: function(tr, product)
		{
			var img = $('<img/>', {
				'src': '/images/ez_prod/' + product.merkid + '/' + product.product_id + '/tn1/' + product.photo
			});
			tr.append($('<td/>').append(img));
			return this;
		},
		addMerk: function(tr, product)
		{
			var td = $('<td/>', {
				text: product.merknaam
			});
			tr.append(td);
			return this;
		},
		addPrijs: function(tr, product)
		{
			var td = $('<td/>', {
				html: function(){
					if(product.prijs)
					{
						return '&euro; ' + product.prijs.prijs
					}
					return '&nbsp;';
				}
			});
			tr.append(td);
			return this;
		},
		addPromotie: function(tr, product)
		{
			var td = $('<td/>', {
				html: function(){
					if(product.promotie)
					{
						return product.promotie.promo;
					}
				}
			});
			tr.append(td);
			//promo tot
			td = $('<td/>', {
				html: function()
				{
					if(product.promotie)
					{
						return product.promotie.stop;
					}
				}
			});
			tr.append(td);
			return this;
		},
		addCustomLabel: function(tr, product)
		{
			var td = $('<td/>');
			tr.append(td);
			return this;
		},
		addActions: function(tr)
		{
			var td = $('<td/>');
			td.append($('<img/>', {
				'src':  '/images/bo/icons/label_icon.gif',
				'class': 'customise'
			}));
			tr.append(td);
			td = $('<td/>');
			td.append($('<img/>', {
				'src':  '/images/bo/icons/cross.png',
				'class': 'dequeue'
			}));
			tr.append(td);
			return this;

		},
		addArtikelInfo: function(tr, product)
		{
			var td = $('<td/>');
			td.append($('<a/>', {
				'href': '#',
				'class': 'inspectCategory',
				'text': product.category,
				'data-category-id': product.category_id
			}));
			td.append($('<br/>'));
			td.append($('<a/>',{
				'href': '#',
				'class': 'inspect',
				'text': product.title
			}));
			tr.append(td);
			return this;
		}


	};

	sos.etiketten.queue = new Queue();

	$('#queueTable').on('click', '.dequeue', function(){
		sos.etiketten.queue.delete($(this).closest('tr'));
	});

})(window.jQuery, window.sos);