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
					prodid: prodid,
					datum: that.getDatum()
				},
				success: function(product)
				{
					that.addRow(product);
				}
			});
		},
		delete: function(tr)
		{
			sos.confirmation({
				textNl: 'Wilt u dit product uit de lijst verwijderen?',
				confirm: function(){
					var prodid = tr.data('prodid');
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
			});
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
			tr.append($('<td/>', {
				'align': 'center'
			}).append(img));
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
						return '&euro;&nbsp;' + product.promotie.promo;
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
		},
		reload: function(prodid)
		{
			var that = this;
			$.ajax({
				url: 'ajax/etiketten.php',
				type: 'GET',
				dataType:'json',
				data: {
					action: 'reloadProduct',
					'product-id': prodid,
					datum: that.getDatum()
				},
				success: function(product){
					var tr = $('#queueTable').find('tr[data-prodid=' + prodid + ']');
					that.reloadRow(tr, product);
				}
			});
		},
		getDatum: function(){
			var datum = $("#etiketDatum").datepicker('getDate');
			var format = $("#etiketDatum").datepicker('option', 'dateFormat');
			datum = $.datepicker.formatDate( format, datum);
			return datum;
		},
		reloadRow: function(tr, product)
		{
			//enkel de prijs, promotie en promotot en custom label kolom kan anders zijn.
			var prijs = tr.find('td:nth-child(4)');
			if(product.prijs)
			{
				prijs.html('&euro;&nbsp;' + product.prijs.prijs);
			}
			else{
				prijs.html('&nbsp;');
			}
			var promo = tr.find('td:nth-child(5)');
			var promotot = tr.find('td:nth-child(6)');
			if(product.promotie)
			{
				promo.html('&euro;&nbsp;' + product.promotie.promo);
				promotot.html(product.promotie.stop);
			}
			else
			{
				promo.html('&nbsp;');
				promotot.html('&nbsp;');
			}
			var customLabel = tr.find('td:nth-child(7)');
			if(product.customLabel)
			{
				customLabel.html('');
				var img = $('<img/>', {
					'src':'/images/bo/icons/tick.png'
				});
				customLabel.append(img);
			}
			else{
				customLabel.html('&nbsp;');
			}
		},
		clear: function(){
			$.ajax({
				url : 'ajax/etiketten.php',
				type: 'POST',
				dataType:'json',
				data: {
					action:'clearQueue'
				},
				success: function(){
					$("#queueTable").find('tbody').html('');
				}
			});
		}

	};

	sos.etiketten.queue = new Queue();

	$('#queueTable').on('click', '.dequeue', function(){
		sos.etiketten.queue.delete($(this).closest('tr'));
	});

	$("#primary-app").on('click', '.emptyQueue', function(){
		sos.confirmation({
			textNl: 'Bent u zeker dat u de lijst met af te drukken producten wil leegmaken?',
			confirm: function(){
				sos.etiketten.queue.clear();
			}
		});
	})

})(window.jQuery, window.sos);