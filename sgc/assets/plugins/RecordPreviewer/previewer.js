function setPreviewer(){


	// script path


	var scripts = document.getElementsByTagName('script'),


		src, path;


	for(var i = 0; i < scripts.length; i++){


		var script = scripts[i];


		if(script.src.indexOf('RecordPreview') >= 0){


			src = script.src;


		}


	}


	path = src.substr(0, src.lastIndexOf( '/' )+1 );


	 


	// vars


	var action = path + 'preview.php',


		target = 'preview_frame',


		duration = 800,


		titles_arr = Array(),


		textareas_arr = Array();


	


	// elements


	var $btn = $('<span class="preview_trigger record_opt_btn">Pré-visualizar</span>'),


		$form = $('<form method="post" action="' + action + '" target="' + target + '" style="display:none">'),


		$preview_frame = $('<div class="' + target + '">');


		$iframe = $('<iframe name="' + target + '">'),


		$bar = $('<div class="bar"></div>'),


		$close_btn = $('<span class="close_btn">Fechar</span>');


	


	// set elements


	$('.record_options_pane').prepend($btn);


	$bar.append($close_btn);


	$preview_frame.append($bar);


	$preview_frame.append($iframe);


	$('body').append($preview_frame);


	


	// adjust iframe height


	var setIFrameHeight = function(){


		$iframe.height($(window).height() - $bar.outerHeight());


	};


	


	$(window).on('load resize', function(){


		setIFrameHeight();


	});


	


	// open preview


	$btn.click(function(){


		if(typeof CKEDITOR !== 'undefined') {


			for(var i in CKEDITOR.instances){


				CKEDITOR.instances[i].updateElement();


			}


		}


		


		$('form.form_model .preview_title').each(function(i){


			titles_arr[i] = $(this).clone().attr({name: 'preview_title[]'});


			$(titles_arr[i]).val($(this).val());


			$form.append($(titles_arr[i]));


		});


		


		$('form.form_model textarea').each(function(i){


			textareas_arr[i] = $(this).clone().attr({name: 'preview_content[]'});


			$(textareas_arr[i]).val($(this).val());


			$form.append($(textareas_arr[i]));


		});


		


		// append form


		$('body').append($form);


		


		// submit form


		$form.submit();


		


		// show iframe


		$preview_frame.fadeIn(duration);


	});


	


	// close preview


	$close_btn.click(function(){


		$form.empty().remove();


		$preview_frame.fadeOut(duration);


	});


}