$(document).ready(function(){
	setList();
});

function setList(){
	var pag = "List/view.php";

	var makeQuery = function(){
		var filters_vals_arr = Array();
		if($filters.length){
			$.each($filters, function(i){
				filters_vals_arr[i] = $(this).val();
			});
		}

		$.post(pag, {value:$search_input.val(), filters_arr:filters_vals_arr}, function(data){
			$(".results_pane").html(data);
		});
	}

	var $search_input = $('.records_pane').find('input[name="search_value"]');
	var $filters = $('.records_pane').find('select.filter');

	$search_input.on('keyup', makeQuery);
	$filters.on('change', makeQuery);

	if($filters.length){
		$.each($filters, function(i){
			var $filter = $(this);
			$filter.on('change',function(){
				$.cookie('filter_' + i, $filter.val(), {expires: 2, path: window.location.pathname});
				makeQuery();
			});

			if($.cookie('filter_' + i) == null){
				$.cookie('filter_' + i, '%', {expires: 2, path: window.location.pathname});
			}else{
				$filter.find('option[value="' + $.cookie('filter_' + i) + '"]').attr('selected', 'selected');
			}
		});
	}

	makeQuery();
}
