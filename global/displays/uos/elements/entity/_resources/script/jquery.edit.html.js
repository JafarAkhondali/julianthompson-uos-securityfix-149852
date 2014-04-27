uos.types['entity.edit.html'] = {};

uos.types['entity.edit.html'].extends = null;

uos.types['entity.edit.html'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_entity_edit_html_initialize
	}
}

function uostype_entity_edit_html_initialize($element) {
	alert('uostype_entity_edit_html_initialize');
}
