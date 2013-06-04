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
		this.plugin = $('#propertyPicker');
		// the current state of properties
		this.data = undefined;
		// the original state of properties when the current category was loaded.
		this.original = undefined;
		// the current id for the category being inspected
		this.categoryId = undefined;

		var that = this;
		function init()
		{
			that.plugin.on('click', '.addable', function(){
				that.propertyAdd($(this));
			});
			that.plugin.on('click', '.remove', function(){
				that.propertyRemove($(this).closest('li'));
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
		/**
		 * Use to fetch the properties for this category from the server
		 */
		load: function(){
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
		clear: function(){
			$(this.modal).html('');
		},
		show: function(custom, standard){
			this.displayProperties(custom);
			this.displayAddableCategories(custom, standard);
			this.plugin.fadeIn();
		},
		hide: function(){
			this.plugin.fadeOut();
		},
		/**
		 * Display properties which have been added to the custom sorting in a sortable list
		 */
		displayProperties: function(properties){
			var ul = $('<ul/>');
			for(var i in properties){
				var li = $('<li>' + properties[i].invoernl + '</li>');
				li.attr({
					'data-property-id': properties[i].catinvoerveldid,
					'class': 'removeable ui-icon ui-icon-arrowthick-2-n-s'
				});
				ul.append();
			}
			this.plugin.find('#addedContainer').append(ul);
		},
		/**
		 * Displays the categories which haven't been added yet into the right side of the plugin
		 */
		displayAddableCategories: function(custom, standard)
		{
			var properties = this.getUnsortedProperties(custom, standard);
			var container = this.plugin.find('#addableContainer');
			for(var i in properties)
			{
				var li = $('<li>' + properties[i].invoernl + '</li>');
				li.attr({
					'class':'addable',
					'data-property-id': properties[i].catinvoerveldid
				});
				container.append(li);
			}
		},
		/**
		 * Returns all the properties that haven't been added to the custom list yet
		 */
		getUnsortedProperties: function(custom, standard)
		{
			var selected = this.data.custom;
			var properties = this.data.standard;
			var show = [];
			for(var i in properties)
			{
				var inArray = false;
				for(var j in selected)
				{
					if(selected[j].catinvoerveldid === properties[i].catinvoerveldid)
					{
						inArray = true;
					}
				}
				if(!inArray)
				{
					show.push(properties[i]);
				}
			}
			return show;
		},
		/**
		 * Resets the plugin to the original state when loading the current category
		 */
		reset: function(){
			this.hide();
			this.data = this.original;
			this.show(this.original.custom, this.original.standard);
		},
		makeSortable: function(element)
		{
			//need to adjust the current array
			
			//need to remove it from the right side
			
			//need to add it to the left side
		},
		/**
		 * Event when clicking on a property that needs to be added to the custom sort
		 * this refers to the element that was clicked?
		 */
		propertyAdd: function(element)
		{
			element.toggleClass('addable removeable added');
			element.prepend($("<i/>", {
				'class' : 'ui-icon ui-icon-arrowthick-2-n-s',
				'text' : '&nbsp;'
			})).append($("<i/>", {
				'class' : 'ui-icon ui-icon-close remove',
				'text': '&nbsp;'
			}));
			element.remove().appendTo(this.plugin.find('#addedContainer'));
			setTimeout(function(){
				element.removeClass('added');
			}, 2000);
		},
		/**
		 * Event when clicking on a property that needs to be removed from the custom sort
		 * @param  {[type]} event [description]
		 * @param  {[type]} data  [description]
		 * @return {[type]}       [description]
		 */
		propertyRemove: function(element)
		{
			element.toggleClass('addable removeable removed');
			element.find('.ui-icon').remove();
			element.remove().appendTo(this.plugin.find('#addableContainer'));
			setTimeout(function(){
				element.removeClass('removed');
			}, 2000);
		}
	};

	var instance = new CategoryInspect();

	sos.etiketten.categoryInspect = function(categoryId){
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