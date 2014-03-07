uos.types['field'] = {};

uos.types['field'].extends = uos.types['entity'];

uos.types['field'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_field_initialize
	},
};

function uostype_field_initialize($element,data) {
	//$element.css('border','3px solid green');
	uos.log('uostype_field_initialize',$element.attr('id'),data);
}		