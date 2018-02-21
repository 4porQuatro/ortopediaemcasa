// JavaScript Document


function setPopups(open_btns){


	var popups = $(".curtain");


	var open_btns = $(open_btns);


	var close_btns = $(".close_popup_btn");	


	


	$.each(open_btns, function(i){


		


		$(this).click(function(){


			// show popup


			$($(popups)[i]).fadeIn('fast');


		});


	});


	


	$.each(close_btns, function(i){


		$(this).click(function(){


			$($(popups)[i]).fadeOut('fast');


		});


	});


}