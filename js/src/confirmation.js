(function($, sos){

	'use strict';

	function Confirmation(options)
	{
		var that = this;
		var defaults = {
			selector: '.confirmation',
			textNl: 'Bent u zeker?',
			textFr: 'Etes-vous sûr?',
			titleNl: 'Bevestiging',
			titleFr: 'Confirmation',
			confirmNl: 'Bevestigen',
			confirmFr: 'Confirmez',
			cancelNl: 'Annuleren',
			cancelFr: 'Annulez',
			confirm : function(){

			}
		}
		this.options = $.extend(defaults, options);
		this.init();
	}

	Confirmation.prototype = {
		init: function()
		{
			this.setup();
			this.events();
			this.open();
		},
		setup: function()
		{
			this.modal = $('<div/>', {
				'class': 'confirmation',
				text: this.text('text'),
				append: this.buttons()
			});
			this.modal.dialog({
				autoOpen: false,
				dialogClass: 'no-close',
				modal: true,
				title: this.text('title'),
				closeOnEscape: false
			});
		},
		text: function(field)
		{
			switch(sos.language())
			{
				case 'nl':
					field = field + 'Nl';
					return this.options[field];
				break;
				case 'fr':
					field = field + 'Fr';
					return this.options[field];
				break;
			}
		},
		buttons: function(){
			var confirm = $('<a/>', {
				'class': 'button confirmation-confirm',
				'text': this.text('confirm')
			});
			var cancel = $('<a/>', {
				'class': 'button confirmation-cancel',
				'text': this.text('cancel')
			});
			var holder = $("<div/>", {
				class: 'confirmation-actions',
				append: [confirm, cancel]
			})
			return holder;
		},
		open: function(){
			this.modal.dialog('open');
		},
		close: function(){
			this.modal.dialog('close');
		},
		events: function(){
			var that = this;
			$('.confirmation').on('click', '.confirmation-confirm', function()
			{
				that.confirm();
			});

			$(".confirmation").on('click', '.confirmation-cancel', function()
			{
				that.close();
			})
		},
		confirm: function()
		{
			this.options.confirm();
			this.close();
		}
	}

	sos.confirmation = function(options)
	{
		new Confirmation(options);
	};

})(window.jQuery, window.sos);