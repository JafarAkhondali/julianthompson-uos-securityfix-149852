uos.displays['field.edit.html'] = uos.extenddisplay('field');

uos.displays['field.edit.html'].actions.init.handler = uostype_field_edit_initialize;

console.log('Included display \'field.edit.html\'',uos.displays['field.edit.html']);

function uostype_field_edit_initialize($element,data) {
	//$element.css('border','1px solid green');
	uos.log('uostype_field_edit_initialize',$element.attr('id'),data);
	
	/*
	$element.bind('click', function(event) {
	//	alert('click');
	});
	*/

	$element.find('.editvalue').bind('keypress', function(event) {
		$element.addClass('uos-modified');
		console.log('onchange');
	});
}		