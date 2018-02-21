$(document).ready(function(){


	setMainMenu();


	setFormMenu();


	setLanguageSelector();


});





function setCBTogglers(){


	var $btns = $('.cb_toggler');


	var $main_btn = $btns.first();


	var $slave_btns = $btns.not($btns.first());


	


	$main_btn.on('click', function(){


		$slave_btns.prop('checked', $main_btn.prop('checked')).trigger('change');


	});


	


	$.each($slave_btns, function(){


		$(this).on('click', function(){


			var checked = false;


			$.each($slave_btns, function(){


				if(this.checked){


					checked = true;


				}


			});


			


			$main_btn.prop('checked', checked).trigger('change');


		});


	});


}





function setPermissionsOptions(){


	var $parent_inputs = $('div.permissions_pane > ol > li > input');


	


	$.each($parent_inputs, function(){


		var $input = $(this);


		var $children_inputs = $input.parent().find('ol input');


		


		$input.on('click', function(){


			$children_inputs.prop('checked', $input.prop('checked')).trigger('change');


		});


		


		$.each($children_inputs, function(){


			$(this).on('click', function(){


				var checked = false;


				$.each($children_inputs, function(){


					if(this.checked){


						checked = true;


					}


				});


				


				$input.prop('checked', checked).trigger('change');


			});


		});


	});


}





function setMainMenu(){


	var $wrapper = $('div#menu_wrapper');


	var $submenus = $('ul.menu > li > ul');


	var $content_pane = $('.content_pane');


	var $iframe = $content_pane.find('iframe');


	var duration = 300;


		


	// manage wrapper height


	var setWrapperHeight = function(){


		var height = window.innerHeight - $('div#side_bar header').outerHeight() - $('div#side_bar div.credits').outerHeight();


		$wrapper.height(height);


		$wrapper.mCustomScrollbar("update");


	};


	


	$wrapper.mCustomScrollbar({advanced:{ updateOnContentResize: true}});


	$(window).on('load resize', setWrapperHeight);


	


	$.each($submenus, function(){


		var $submenu = $(this);


		var $trigger = $submenu.parent().children('span');


		var $items = $submenu.find('li');


		var closed = true;


		


		// check if any item is active


		// if an item is active, then this submenu is active too


		$items.each(function(){


			if($(this).hasClass('active')){


				$submenu.parent().addClass('active');


				closed = false;	


			}


		});


		


		$trigger.on('click', function(){


			if(closed){


				$submenu.slideDown(duration, function(){


					$trigger.addClass('active');


					closed = false;


				});


			}else{


				$submenu.slideUp(duration, function(){


					$trigger.removeClass('active');


					closed = true;


				});


			}


		});


	});


}





function setFormMenu(){


	var $btns = $("#form_menu").find("li");


	var $panes = $(".form_model div.form_pane");


	var current = 0;


	


	if($btns.length && $panes.length){


		if($btns.length != $panes.length){


			alert("O número de items do menu do formulário não corresponde ao número de painéis");	


		}


		


		$.each($btns, function(i){


			if($(this).hasClass('current_item')){


				current = i;	


			}


		});


		$btns.eq(current).addClass("current_item");


		$panes.eq(current).show();


		


		$.each($btns, function(i){


			$(this).click(function(){


				if(i != current){


					$panes.eq(current).hide();


					$btns.eq(current).removeClass("current_item");


					


					current = i;


					


					$panes.eq(current).show();


					$btns.eq(current).addClass("current_item");


				}


			});


		});


	}


}





function setLanguageSelector(){


	$("#side_bar [name = language_id]").change(function(){


		console.log($("#side_bar [name = language_id]").val())


		$("form")[0].submit();


	});	


}