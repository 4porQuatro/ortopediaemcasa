/*
*	JQuery Records List Plugin
*	Developed by 4por4
*	Author: José Osório
*/
(function($){
	$.fn.recordsList = function(options){
		var opts = $.extend( {}, $.fn.recordsList.defaults, options ),
			path = $.fn.recordsList.findIncludePath(),
			pathname = window.location.pathname,
			dirname = pathname.substring(0, pathname.lastIndexOf('/'));

		// append CSS
		if(!$('#records_list_styles').length){
			$('head').append('<link id="records_list_styles" rel="stylesheet" type="text/css" href="' + path + 'Assets/layout.css">');
		}

		return this.each(function(){
			// elements
			var $this = $(this),
				$search_input,
				$filters,
				$results_pane,
				$list,
				$delete_btns,
				$open_popup_btn;

			var view_page, controller_page;

			// initialize plugin
			var init = function(){
				view_page = path + 'view.php';
				controller_page = path + 'controller.php';

				$this.prepend('<div class="input_wrapper"><input type="text" name="search_value" placeholder="Pesquisar..." autocomplete="off"></div>');
				$this.append('<div class="results_pane"></div>');

				$search_input = $this.find('input[name="search_value"]');

				if($.cookie('search_needle') != null){
					$search_input.val($.cookie('search_needle')).trigger('change');
				}
				$search_input.on('keyup', function(){
					$.cookie('search_needle', $search_input.val(), {expires: 2, path: dirname});
					makeQuery();
				});

				$filters = $this.find('select');
				if($filters.length){
					$.each($filters, function(i){
						var $filter = $(this);

						$filter.on('change',function(){
							$.cookie('filter_' + i, $filter.val(), {expires: 2, path: dirname});
							makeQuery();
						});

						if($.cookie('filter_' + i) == null){
							$.cookie('filter_' + i, '%', {expires: 2, path: dirname});
						}else{
							$filter.find('option[value="' + $.cookie('filter_' + i) + '"]').attr('selected', 'selected');
							$filter.trigger('change');
						}
					});
				}

				$results_pane = $this.find('.results_pane');

				makeQuery ();
			};

			// update elements
			var updateEls = function(){
				$list = $results_pane.find('tbody');
				$delete_btns = $results_pane.find('.sprite.delete_record').on('click', deleteRecord);
				$open_popup_btn = $results_pane.find('.sprite.database.upload').on('click', setPopup);

				if(opts.sortable && $list.find('tr').length > 1){
					var priorities_arr = Array();
					$.each($list.find('tr'), function(i){
						priorities_arr[i] = $(this).data('priority');
					});

					// fix cell width for sortable rows
					$list.find('tr').css({cursor: 'move'});
					$.each($list.find('td'), function(i){
						$(this).css({width: $(this).width() + 'px'});
					});

					$list.sortable({
						placeholder: 'records_list_placeholder',
						containment: $list.parent(),
						tolerance: 'pointer',
						update: function(event, ui){
							updateOrder($list.sortable("toArray"), priorities_arr);
						}
					});
				}
			};

			// set folder
			var setFolder = function(){
				$.post(
					controller_page,
					{
						op:				'set_folder'
					},
					function(data){
						if(!data){
							alert('Ocorreu um erro na criação da pasta de upload dos CSVs!');
						}
					}
				);
			};

			// set Pop up
			var setPopup = function(){
				$.post(
					controller_page,
					{
						op: 'get_table_cols',
						table: opts.table
					},
					function(data){
						var cols_arr = eval(data);

						var html = '<div class="popup">';
						html += '<div class="popup_pane">';
						html += '<span class="close_popup_btn">FECHAR</span>';
						html += '<h2>Importação de dados</h2>';
						html += '<h3>Selecione um ficheiro a partir do seu computador para importar dados para a tabela.</h3>';
						html += '<p><b>Notas:</b></p>';
						html += '<b>1.</b> O ficheiro deverá estar no formato <i>.csv</i>.<br>';
						html += '<b>2.</b> As colunas devem estar na seguinte ordem: ' + cols_arr.join(', ') + '.';
						html += '<hr class="hline">';
						html += '<div class="op_result_pane"></div>';
						html += '<input type="file" name="csv_file">';
						html += '<input type="submit" value="importar">';
						html += '</div>';
						html += '</div>';

						$('body').append(html);

						var $popup = $('body').find('.popup');
							$close_popup_btn = $popup.find('.close_popup_btn'),
							$result_pane = $popup.find('.op_result_pane'),
							$file_input = $popup.find('input[type="file"]'),
							$submit_btn = $popup.find('input[type="submit"]');

						var duration = 400;

						// open popup
						$popup.fadeIn(duration);

						// close popup
						$close_popup_btn.on('click', function(){
							$popup.fadeOut(duration);
						});

						$submit_btn.on('click', function(e){
							e.preventDefault();
							var file = $file_input.get(0).files[0];

							if(file){
								var formData = new FormData();									// creat formdataObject
								formData.append('op', 'import_records');						// operation type
								formData.append('table', opts.table);							// the images table
								formData.append('csv_file', file);								// appending the file to be uploaded
								formData.append('path', path);									// folder path to store csv file

								$.ajax({
									url: controller_page,  										//server script to process data
									type: 'POST',
									data: formData,
									cache: false,
									contentType: false,
									processData: false
								}).done(function(data){
									console.log(data)
									if(data == ""){
										$result_pane.html('<p class="popup_op ok">A importação foi concluída com sucesso!</p>');
										$result_pane.find('p').fadeIn(400);
									}else{
										$result_pane.html('<p class="popup_op nok">' + data + '</p>');
										$result_pane.find('p').fadeIn(400);
									}
									// hide op result message
									window.setTimeout(
										function(){
											//$result_pane.find('p').fadeOut(400);
										},
										5000
									);
									// refresh list
									makeQuery();
								}).fail(function(){
									alert("Ocorreu um erro inesperado. Por favor, tente novamente.");
								});
							}else{
								$result_pane.html('<p class="popup_op nok">Selecione um ficheiro.</p>');
								$result_pane.find('p').fadeIn(400);
								window.setTimeout(
									function(){
										$result_pane.find('p').fadeOut(400);
									},
									5000
								);
							}
						});
					}
				);
			};

			// update order
			var updateOrder = function(ids_arr, priorities_arr){
				$.post(
					controller_page,
					{
						op: 'order',
						table: opts.table,
						primary_key: opts.primary_key,
						ids_arr: ids_arr,
						priorities_arr: priorities_arr,
						language_id: opts.language_id
					}
				);
			};

			// delete record
			var deleteRecord = function(e){
				if(!confirm("Esta operação eliminará permanentemente o registo!\nDeseja prosseguir?"))
					return;

				var $el = $(e.target);
				$.post(
					controller_page,
					{
						op: 'delete',
						table: opts.table,
						primary_key: opts.primary_key,
						language_id: opts.language_id,
						id: $el.data('id'),
						sortable: opts.sortable
					},
					function(data){
						makeQuery();
					}
				);
			};

			// make query
			var makeQuery  = function(){
				var filters_vals_arr = Array();
				if($filters.length){
					$.each($filters, function(i){
						filters_vals_arr[i] = $(this).val();
					});
				}

				$.post(
					view_page,
					{
						table: opts.table,
						primary_key: opts.primary_key,
						columns: opts.columns,
						search_value: $search_input.val(),
						filters_arr: opts.filters_arr,
						filters_vals_arr: filters_vals_arr,
						language_id: opts.language_id,
						sort_field: opts.sort_field,
						sort_order: opts.sort_order,
						removable: opts.removable,
						sortable: opts.sortable,
						edit_page: opts.edit_page,
						extra_btns: opts.extra_btns,
						import_data: opts.import_data,
						export_data: opts.export_data,
						path: path
					},
					function(data){
						$results_pane.html(data);
						updateEls();
					}
				);
			};

			init();
			setFolder();
		});
	};

	$.fn.recordsList.defaults = {
		table: null,
		primary_key: null,
		columns: ['title'],
		filters_arr: [],
		language_id: null,
		sort_field: null,
		sort_order: 'ASC',
		removable: true,
		sortable: false,
		edit_page: 'edit.php',
		extra_btns: [],
		import_data: true,
		export_data: true,
	};

	/*
	*	FIND INCLUDE PATH
	*/
	$.fn.recordsList.findIncludePath = function(){
		var scripts = document.getElementsByTagName('script'),
			src, path;

		for(var i = 0; i < scripts.length; i++){
			var script = scripts[i];
			if(script.src.indexOf('RecordsList') >= 0){
				src = script.src;
			}
		}

		path = src.substr(0, src.lastIndexOf('/') + 1);
		return  path;
	};
}( jQuery ));
