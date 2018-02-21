var pag = "List/view.php";
$(document).ready(function() {
	$(makeQuery(true));
});
function setPriority(el){
	var data = el.split(";");
	var priority_hash = data[0];
	var priority = data[1];
	var parent_id = data[2];
	var direction = data[3];
	var scroll_pos = $(window).scrollTop();

	$.post(pag, {priority_hash:priority_hash, priority:priority, parent_id:parent_id, direction:direction}, function(data){
		$("#results").html(data);
		$(window).scrollTop(scroll_pos);
	});
}
function deleteRecord(delete_hash){
	if(!confirm("Esta operação eliminará permanentemente o registo!\nDeseja prosseguir?"))
		return;

	var scroll_pos = $(window).scrollTop();

	$.post(pag, {delete_hash:delete_hash}, function(data){
		$("#results").html(data);
		$("#results").css("display", "none");
		$("#results").fadeIn("slow");
		$(window).scrollTop(scroll_pos);
	});
}
function makeQuery(animate){
	var scroll_pos = $(window).scrollTop();

	$.post(pag, {}, function(data){
		$("#results").html(data);
		if(animate){
			$("#results").css("display", "none");
			$("#results").fadeIn("slow");
		}
		$(window).scrollTop(scroll_pos);
	});
}
