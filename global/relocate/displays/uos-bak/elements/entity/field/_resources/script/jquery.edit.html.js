uos.displays['field.edit.html'] = {};

uos.displays['field.edit.html'].extends = uos.displays['entity'];

uos.displays['field.edit.html'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_field_edit_initialize
	},
};

function uostype_field_edit_initialize($element,data) {
	//$element.css('border','1px solid green');
	uos.log('uostype_field_edit_initialize',$element.attr('id'),data);
	$element.bind('click', function(event) {
		alert('click');
	});
}		