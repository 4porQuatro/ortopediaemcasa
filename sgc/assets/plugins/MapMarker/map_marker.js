function initMapMarker(){


	var $map = $('div.map_marker');


	var $top_pos_input = $map.find('input[name="pin_top_pos"]');


	var $left_pos_input = $map.find('input[name="pin_left_pos"]');


	var offset, x_pos = $left_pos_input.val(), y_pos = $top_pos_input.val();


	


	if(x_pos && y_pos){


		$map.append('<span class="map_pin"></span>');


		$pin = $map.find('.map_pin');


		$pin.css({top: y_pos + 'px', left: x_pos + 'px'});


	}


	


	$map.mousemove(function(e){


		offset = $map.offset();


		x_pos = Math.round(e.pageX - offset.left);


		y_pos = Math.round(e.pageY - offset.top);


	});


	


	$map.click(function(){


		var $pin = $map.find('.map_pin');


		


		if($pin.length){


			$pin.css({top: y_pos, left: x_pos});


		}else{


			$map.append('<span class="map_pin"></span>');


			$pin = $map.find('.map_pin').css({top: y_pos, left: x_pos});


		}


		$left_pos_input.val(x_pos);


		$top_pos_input.val(y_pos);


		console.log(x_pos + " " + y_pos);


	});


}