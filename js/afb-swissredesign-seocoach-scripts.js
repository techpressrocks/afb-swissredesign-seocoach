jQuery(document).ready(function($){
	$('.sr_design_primarycolor').wpColorPicker();
	$('.sr_design_secondarycolor').wpColorPicker();

/*	$('#meta-image-button').click(function() {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		wp.media.editor.send.attachment = function(props, attachment) {
			$('#meta-image').val(attachment.url);
			$('#meta-image-preview').attr('src',attachment.url);
			wp.media.editor.send.attachment = send_attachment_bkp;
		}
		wp.media.editor.open();
		return false;
	});*/
	
	$('.metabox_submit').click(function(e) {
		e.preventDefault();
		$('#publish').click();
	});
	$('#add-row').on('click', function() {
		var row = $('.empty-row.screen-reader-text').clone(true);
		row.removeClass('empty-row screen-reader-text');
		row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
		return false;
	});
	$('.remove-row').on('click', function() {
		$(this).parents('tr').remove();
		return false;
	});
	$('.datepicker').datetimepicker();
	$('.datepicker').timepicker(
		$.timepicker.regional['de']
	);
});	