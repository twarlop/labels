var sos = {
	'author': 'Thomas Warlop',
	'etiketten':{
		'version': '1.0.0'
	}
};

window.sos = sos;


(function($, sos){
	'use strict';

	$(document).ready(function(){

		$("#queueTable").on('click', '.inspect', function(){
			var prodid = parseInt($(this).closest('tr').data('prodid'), 10);
			$.inspectReborn({
				'prodid':prodid,
				'after': function(){
					sos.etiketten.queue.reload(prodid);
				}
			});
		});

	})

})(window.jQuery, window.sos);
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
					'product-id': prodid
				},
				success: function(product){
					var tr = $('#queueTable').find('tr[data-prodid=' + prodid + ']');
					that.reloadRow(tr, product);
				}
			});
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
		sos.etiketten.queue.clear();
	})

})(window.jQuery, window.sos);
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


		$('#etiketDatum').datepicker({
			dateFormat: 'dd/mm/yy'
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
(function($, sos){

	'use strict';

	var CategoryInspect = function()
	{
		this.plugin = $('#propertyPicker').hide();
		// the original state of properties when the current category was loaded.
		this.original = undefined;
		// the current id for the category being inspected
		this.categoryId = undefined;
		this.primaryApp = $("#primary-app");

		var that = this;
		function init()
		{
			that.plugin.on('click', '.addable', function(){
				that.propertyAdd($(this));
			});
			that.plugin.on('click', '.remove', function(){
				that.propertyRemove($(this).closest('li'));
			});
			that.plugin.on('click', '.close-app', function(){
				that.close(true);
			});
			that.plugin.on('click', '.full-reset-properties', function(){
				that.reset();
			})
			that.plugin.on('click', '.reset-properties', function(){
				that.undo();
			});
			that.plugin.on('click', '.submit-properties', function(){
				that.save();
			});
			that.plugin.find('#addedContainer').sortable({
				placeholder: "ui-state-highlight",
				forcePlaceholderSize: true
			}).disableSelection();
		}

		init();
	};

	CategoryInspect.prototype = {
		inspect: function(categoryId)
		{
			this.categoryId = parseInt(categoryId, 10);
			if(!isNaN(this.categoryId))
			{
				this.clear(true);
				this.load();
			}
		},
		close: function(needConfirm)
		{
			if(needConfirm === true)
			{
				var confirmed = window.confirm('You are about to loose changed');
				if(confirmed)
				{
					this.hide();
				}
			}
			else
			{
				this.hide();
			}
		},
		save: function()
		{
			var that = this;
			var sorting = that.getSorting();
			$.ajax({
				url:'ajax/etiketten.php',
				data:{
					action: 'saveCategory',
					categoryId: that.categoryId,
					properties: sorting
				},
				type:'POST',
				dataType: 'json',
				success: function(){
					that.close(false);
				}
			});
		},
		getSorting: function()
		{
			var sorting = []
			this.plugin.find('#addedContainer li').each(function(index, element){
				sorting.push($(element).data('property-id'));
			});
			return sorting;
		},
		/**
		 * Use to fetch the properties for this category from the server
		 */
		load: function()
		{
			var that = this;
			$.ajax({
				url:'ajax/etiketten.php',
				data:{
					action: 'loadCategory',
					categoryId: this.categoryId
				},
				type:'GET',
				dataType:'json',
				success: function(response){
					that.data = response;
					//save original to be able to reset to original
					that.original = response;
					that.displayStandard(response.standard);
					that.show(response.custom, response.standard);
				}
			});
		},
		/**
		 * Cleans the modal up before we will fill it with a new category
		 */
		clear: function(clearStandard)
		{
			if(clearStandard)
				this.plugin.find('#standard').html('');
			this.plugin.find('#addedContainer, #addableContainer').html('');
		},
		show: function(custom, standard)
		{
			var that = this;
			that.displayProperties(custom);
			that.displayAddableProperties(standard);
			that.primaryApp.fadeOut(function(){
				that.plugin.fadeIn();
			});
			
		},
		hide: function()
		{
			var that = this;
			that.plugin.fadeOut(function(){
				that.primaryApp.fadeIn();
			});
		},
		/**
		 * Display properties which have been added to the custom sorting in a sortable list
		 */
		displayProperties: function(properties)
		{
			var ul = $('#addedContainer').html('');
			for(var i in properties){
				var li = $('<li>' + properties[i].invoernl + '</li>', {
					'class': 'removeable ui-icon ui-icon-arrowthick-2-n-s',
					'text': properties[i].invoernl
				});
				li.attr({
					'data-property-id': properties[i].catinvoerveldid
				});
				li = this.addIcons(li);
				ul.append(li);
			}
		},
		/**
		 * Displays the properties which haven't been added yet into the right side of the plugin
		 */
		displayAddableProperties: function(properties)
		{
			var container = this.plugin.find('#addableContainer');
			for(var i in properties)
			{
				var li = $('<li>' + properties[i].invoernl + '</li>');
				li.attr({
					'class':'addable',
					'data-property-id': properties[i].catinvoerveldid
				});
				if(this.isSorted(properties[i].catinvoerveldid))
				{
					li.hide();
				}
				container.append(li);
			}
		},
		displayStandard: function(properties)
		{
			var container = this.plugin.find('#standard');
			for(var i in properties)
			{
				var li = $('<li/>', {
					text: properties[i].invoernl
				});
				container.append(li);
			}
		},
		/**
		 * check if a property is allready sorted
		 */
		isSorted: function(property)
		{
			var custom = this.original.custom;
			for(var i in custom)
			{
				if(property === custom[i].catinvoerveldid)
				{
					return true;
				}
			}
			return false;
		},
		/**
		 * Resets the plugin to the original state when loading the current category
		 */
		reset: function(){
			this.plugin.find('#addedContainer').html('');
			this.plugin.find('#addableContainer li').show();
		},
		undo: function()
		{
			this.plugin.hide();
			this.clear();
			this.displayProperties(this.original.custom);
			this.displayAddableProperties(this.original.standard);
			this.plugin.show();
		},
		/**
		 * Event when clicking on a property that needs to be added to the custom sort
		 * this refers to the element that was clicked?
		 */
		propertyAdd: function(element)
		{
			element.hide();
			element = element.clone();
			element = this.addIcons(element);
			element.show().toggleClass('addable removeable added');
			element.appendTo(this.plugin.find('#addedContainer'));
			setTimeout(function(){
				element.removeClass('added');
			}, 2000);
		},
		addIcons: function(element)
		{
			element.prepend($("<i/>", {
				'class' : 'ui-icon ui-icon-arrowthick-2-n-s',
				'text' : '&nbsp;'
			})).append($("<i/>", {
				'class' : 'ui-icon ui-icon-close remove',
				'text': '&nbsp;'
			}));
			return element;
		},
		/**
		 * Event when clicking on a property that needs to be removed from the custom sort
		 */
		propertyRemove: function(element)
		{
			element.remove();
			var original = this.plugin.find('#addableContainer li[data-property-id=' + element.data('property-id') + ']')
			original.addClass('removed').show();
			setTimeout(function(){
				original.removeClass('removed');
			}, 2000);
		}
	};

	var instance = new CategoryInspect();

	sos.etiketten.categoryInspect = function(categoryId)
	{
		instance.inspect(categoryId);
	};

})(window.jQuery, window.sos);
(function($, sos){

	'use strict';

	$(document).ready(function(){

		$('#queueProduct').autocomplete({
			source: function(item, response){
				$.ajax({
					url:'ajax/etiketten.php',
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