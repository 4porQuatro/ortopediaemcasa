/*
*	JQuery videos Uploader Plugin v2.0
*	Developed by 4por4
*	Author: José Osório
*/
(function($){
	$.fn.videosUploader = function(options){
		var path = $.fn.videosUploader.findIncludePath();

		// append CSS
		if(!$('#videos_uploader_styles').length){
			$('head').append('<link id="videos_uploader_styles" rel="stylesheet" type="text/css" href="' + path + 'assets/css/layout.css" media="all">');
		}

		return this.each(function(){
			// elements
			var $this = $(this),
				$file_input,
				$wrapper,
				$placeholder,
				$list,
				$title_input,
				$delete_btns;
			// paths and files
			var controller_page, view_page;
			// plugin vars
			var opts;

			// initialize plugin
			var init = function(){
				opts = $.extend($.fn.videosUploader.defaults, options);

				controller_page = path + 'controller.php';
				view_page = path + 'view.php';

				setup();
			};

			// setup plugin
			var setup = function(){
				setFolder();
				setElements();
				updateView();
			};

			// set file input
			var setElements = function(){
				var kbs = opts.max_size / 1024;
				var mbs = kbs / 1024;

				$this.wrap('<div class="video_placeholder"></div>');
				$placeholder = $this.parent();
				$placeholder.wrap('<div class="dup_wrapper"></div>');
				$wrapper = $placeholder.parent();
				$placeholder.append('<input class="dup_file" type="file">');
				$placeholder.append('<div class="button">clique aqui para selecionar um vídeo</div>');
				$placeholder.after('<ul class="sortable_videos"></ul><hr class="clear">');
				$placeholder.before('<hr class="clear"><table><tr><td><b>Notas:</b><br><b>1.</b> Os vídeos podem ter o tamanho máximo de ' + mbs + ' MB.<br><b>2.</b> Os vídeos devem estar num dos seguintes formatos: <i>' + opts.allowed_formats.join(', ').toUpperCase() + '</i>.<br></td></tr></table>');

				// set file input
				$file_input = $this.parent().find('.dup_file');

				$file_input.attr({
					name:		$this.attr('name') + '_dup[]',
					multiple:	true,
					accept:		'.' + opts.allowed_formats.join(',.')
				});

				// set list
				$list = $wrapper.find('.sortable_videos');

				// set file input onChange event
				$file_input.on('change', uploadFiles);
			}

			// update elements
			var updateElements = function(){
				$title_input = $list.find('input[name="video_title[]"]')
				$title_input.on('keyup', updateTitle);
				$delete_btns = $list.find('.delete_video_btn').on('click', deleteFile);

				$list.sortable({
					placeholder: 'placeholder',
					tolerance: 'pointer',
					update: function(event, ui){
						updateOrder($list.sortable("toArray"));
					}
				});
			};

			// set folder
			var setFolder = function(){
				$.post(
					controller_page,
					{
						op:				'set_folder',
						subfolder:		opts.subfolder
					},
					function(data){
						if(!data){
							alert('Ocorreu um erro na criação da pasta de upload dos ficheiros!');
						}
					}
				);
			};

			// upload files
			var uploadFiles = function(e){
				var files = e.target.files;
				var errors = "";

				if(files){
					// check for errors
					for(var i = 0; i < files.length; i++){
						if(files[i].size > opts.max_size){
							errors += "O tamanho do vídeo \"" + files[i].name + "\" não é válido.\n";
						}
						var name_arr = files[i].name.split('.');
						var file_extension = name_arr[name_arr.length - 1];
						if(opts.allowed_formats.indexOf(file_extension) < 0){
							errors += "O formato do vídeo \"" + files[i].name + "\" não é válido.\n";
						}
					}

					if(errors != ""){
						alert(errors);
					}else{
						var formData = new FormData();										// creat formdataObject
						formData.append('op', 'upload');									// operation type
						for(var i = 0; i < files.length; i++){
							formData.append('files[]', files[i]);							// appending the file to be uploaded
						}
						formData.append('value', $this.val());								// current input value
						formData.append('allowed_formats', opts.allowed_formats);			// allowed file formats
						formData.append('max_size', opts.max_size);							// max upload size
						formData.append('subfolder', opts.subfolder);						// subfolder

						$.ajax({
							url: controller_page,  										//server script to process data
							type: 'POST',
							data: formData,
							dataType: "json",
							cache: false,
							contentType: false,
							processData: false
						}).done(function(data){
							$this.val(JSON.stringify(data.files));

							updateView();
						}).fail(function(){
							alert("Ocorreu um erro inesperado. Por favor, tente novamente.");
						});
					}
				}
			}

			// update order
			var updateOrder = function(order_arr){
				$.post(
					controller_page,
					{
						op:				'update_order',
						value:			$this.val(),
						order_arr: 		order_arr
					},
					function(data){
						$this.val(JSON.stringify(data));

						updateView();
					},
					"json"
				);
			};

			// update video title
			var updateTitle = function(e){
				var $el = $(e.target);
				var arr_id = $el.closest('li').data('id');

				$.post(
					controller_page,
					{
						op: 			'update_title',
						value:			$this.val(),
						array_index:	$el.closest('li').prop('id'),
						title: 			$el.val()
					},
					function(data){
						$this.val(JSON.stringify(data));
					},
					"json"
				);
			};

			// delete file
			var deleteFile = function(e){
				if(!confirm("O ficheiro será eliminada permanentemente!\nApós eliminação, deve gravar o registo para atualizar a informação na base de dados.\nDeseja prosseguir?"))
					return;

				var $el = $(e.target);
				$.post(
					controller_page,
					{
						op				: 'delete_file',
						value			: $this.val(),
						array_index		: $el.closest('li').prop('id'),
						subfolder		: opts.subfolder
					},
					function(data){
						$this.val(JSON.stringify(data));

						updateView();
					},
					"json"
				);
			};

			// update view
			var updateView = function(){
				$.post(
					view_page,
					{
						value			: $this.val(),
						subfolder		: opts.subfolder
					},
					function(data){
						$list.html(data);
						updateElements();
					}
				);
			};

			init();
		});
	};

	$.fn.videosUploader.defaults = {
		allowed_formats		: ['mp4'],
		max_size			: 104857600,
		subfolder			: ''
	};

	/*
	*	FIND INCLUDE PATH
	*/
	$.fn.videosUploader.findIncludePath = function(){
		var scripts = document.getElementsByTagName('script'),
			src, path;

		for(var i = 0; i < scripts.length; i++){
			var script = scripts[i];
			if(script.src.indexOf('VideosUploader') >= 0){
				src = script.src;
			}
		}

		path = src.substr(0, src.lastIndexOf('/') + 1);
		return  path;
	};
}( jQuery ));
