var sos = {
	'author': 'Thomas Warlop',
	'etiketten':{
		'version': '1.0.0'
	}
};

window.sos = sos;
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
				this.clear();
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
				else
				{
					return;
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
					action: 'saveSorting',
					categoryId: that.categoryId,
					sorting: sorting
				},
				type:'POST',
				dataType: 'json',
				success: function(){
					that.close(false);
				}
			})
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
					that.show(response.custom, response.standard);
				}
			});
		},
		/**
		 * Cleans the modal up before we will fill it with a new category
		 */
		clear: function()
		{
			$(this.modal).html('');
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
				var li = $('<li>' + properties[i].invoernl + '</li>');
				li.attr({
					'data-property-id': properties[i].catinvoerveldid,
					'class': 'removeable ui-icon ui-icon-arrowthick-2-n-s'
				});
				ul.append();
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
					this.hide();
				}
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
				if(property === custom.catinvoerveldid)
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
			this.plugin.find('#addedContainer, #addableContainer').html('');
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
			element.prepend($("<i/>", {
				'class' : 'ui-icon ui-icon-arrowthick-2-n-s',
				'text' : '&nbsp;'
			})).append($("<i/>", {
				'class' : 'ui-icon ui-icon-close remove',
				'text': '&nbsp;'
			}));
			element.show().toggleClass('addable removeable added');
			element.appendTo(this.plugin.find('#addedContainer'));
			setTimeout(function(){
				element.removeClass('added');
			}, 2000);
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

	});

})(window.jQuery, window.sos);