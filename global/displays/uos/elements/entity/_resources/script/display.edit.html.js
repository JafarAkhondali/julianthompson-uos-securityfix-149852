uos.displays['entity.edit.html'] = {};

uos.displays['entity.edit.html'].extends = null;

uos.displays['entity.edit.html'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_entity_edit_html_initialize
	}
}

function uostype_entity_edit_html_initialize($element) {
	uostype_entity_initialize($element);
	alert('hey');
	$element.find('.uos-window-close').css('border','1px solid green');
	//uos.log('uostype_entity_edit_html_initialize');
}