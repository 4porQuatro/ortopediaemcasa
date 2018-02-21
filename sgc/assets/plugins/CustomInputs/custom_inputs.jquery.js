/*
*	JQuery Custom Inputs Plugin
*	Developed by 4por4
*	Author: José Osório
*/
(function($){
	/*
	*	CUSTOM SELECT
	*/
	$.fn.customSelect = function(){
		return this.each(function(){
			var $el = $(this),
				$wrapper,
				$displayer,
				$button;

			// wrap select box with a customizable div
			$el.wrap('<div class="custom_select">');
			// get jQuery access of the wrapper
			$wrapper = $el.parent();
			$wrapper.prepend('<div class="cs_displayer"></div>');
			$displayer = $wrapper.find('.cs_displayer');

			// create span for the custom button
			$button = $('<span></span>');
			// append button to the wrapper
			$wrapper.append($button);
			// set wrapper's mandatory styles
			$wrapper.css({
				zIndex: 0,
				position: 'relative'
			});

			// check if select is disabled
			if($el.is(':disabled')){
				$wrapper.addClass('disabled');
			}

			// copy other classes
			$wrapper.addClass($el.attr('class'));

			// update selected option on load
			$displayer.html($el.find(':selected').html());

			// style select box
			$el.css({
				width: '100%',
				height: '100%',
				lineHeight: '100%',
				padding: 0,
				border: 'none',
				opacity: 0,
				position: 'absolute',
				top: ($wrapper.innerHeight() - $wrapper.outerHeight()) / 2,
				left: ($wrapper.innerWidth() - $wrapper.outerWidth()) / 2,
				zIndex: 1,
				cursor: $wrapper.css('cursor'),
				color: $wrapper.css('color')
			});

			// update selected option on change
			$el.on('change', function(){
				$displayer.html($el.find(':selected').html());
				if(!$el.is(':disabled')){
					$wrapper.removeClass('disabled');
				}else{
					$wrapper.addClass('disabled');
				}
			});

			// change wrapper style on focus/blur
			$el.on('focus', function(){
				$wrapper.addClass('active');
			});
			$el.on('blur', function(){
				$wrapper.removeClass('active');
			});
		});
	};


	/*
	*	CUSTOM CHECK BOX
	*/
	$.fn.customCheckBox = function(){
		return this.each(function(){
			$el = $(this);
			$el.wrap('<div class="custom_checkbox">');
			var $wrapper = $el.parent();
			$wrapper.append('<span></span>');
			var $square = $wrapper.find('span');

			// check if checkbox is checked
			if($el.is(':checked')){
				$square.addClass('current');
			}

			// check if checkbox is disabled
			if($el.is(':disabled')){
				$wrapper.addClass('disabled');
			}

			$el.on('change', function(){
				if(this.checked){
					$square.addClass('current');
				}else{
					$square.removeClass('current');
				}
			});
		});
	};


	/*
	*	CUSTOM RADIO BUTTON
	*/
	$.fn.customRadioButton = function(){
		var $els = $(this);
		return this.each(function(){
			$el = $(this);
			$el.wrap('<div class="custom_radio">');
			var $wrapper = $el.parent();
			$wrapper.append('<span></span>');
			var $circle = $wrapper.find('span');

			// check if radio button is checked
			if($el.is(':checked')){
				$circle.addClass('current');
			}

			// check if radio button is disabled
			if($el.is(':disabled')){
				$wrapper.addClass('disabled');
			}

			$el.change(function(){
				$.each($els, function(){
					if($(this).is(':checked')){
						$(this).parent().find('span').addClass('current');
					}else{
						$(this).parent().find('span').removeClass('current');
					}
				});
			});
		});
	};
}( jQuery ));
