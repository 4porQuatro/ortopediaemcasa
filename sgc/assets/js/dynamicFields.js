function dynamicFields(add_btn, remove_btns, rows){
	var $rows,
		$remove_btns,
		$add_btn = $(add_btn);
	var c = 0;

	var setElements = function(){
		$remove_btns = $(remove_btns);
		$rows = $(rows);

		$remove_btns.off('click');

		$remove_btns.each(function(i){
			var $btn = $(this);
			$btn.on('click', function(){
				if($rows.length <= 1){
					alert("Não é possível remover a última linha!\nSe deseja descartar o registo, limpe os campos.");
				}else{
					$rows.eq(i).remove();

					setElements();
				}
			});
		});
	};

	// add
	$add_btn.on('click', function(){
		var $last_el = $rows.last();

		if($last_el.find('textarea').length){
			for(name in CKEDITOR.instances){
				CKEDITOR.instances[name].destroy();
			}
		}

		var $new_el = $last_el.clone();
		$new_el.find('input').val('');
		$last_el.after($new_el);

		/* rebuild custom selectors */
		$new_el.find('.custom_select').each(function(){
			var $el = $(this),
				$select = $el.find('select');

			// remove selected option
			$select.find('option:selected').prop('selected', false);

			// rebuild custom select
			$el.before($select);
			$el.remove();
			$select.customSelect();
		});

		/* rebuild custom checkboxes */
		$new_el.find('.custom_checkbox').each(function(){
			var $wrapper = $(this),
				$checkbox = $wrapper.find('input');

			$checkbox.prop('checked', false);

			// rebuild custom checkbox
			$wrapper.before($checkbox);
			$wrapper.remove();
			$checkbox.customCheckBox();
		});

		/* rebuild custom radio buttons */
		$new_el.find('.custom_radio').each(function(){
			var $wrapper = $(this),
				$radio = $wrapper.find('input');

			$radio.prop('checked', false);

			// rebuild custom checkbox
			$wrapper.before($radio);
			$wrapper.remove();
			$radio.customRadioButton();
		});

		if($new_el.find('textarea').length){
			CKEDITOR.replaceAll(function( textarea, config ){});
		}

		setElements();
	});

	setElements();
}
