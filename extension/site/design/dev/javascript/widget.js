function opencomments(id) {
	if ($('#com_wrapper_' + id + ' #thecomform').length) {
		$('#com_wrapper_' + id).slideDown();
		return false;
	} else {
		$('#thecomform').appendTo($('#com_wrapper_' + id));
		$('#com_wrapper_' + id).slideDown();
		$("#cur_ob_id").val(id);
		return false;
	}
}
