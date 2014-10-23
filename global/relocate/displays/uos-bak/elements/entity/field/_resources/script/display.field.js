uos.displays['field'] = {};

uos.displays['field'].extends = uos.displays['entity'];

uos.displays['field'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_field_initialize
	},
};

function uostype_field_initialize($element,data) {
	//$element.css('border','1px solid green');
	//uos.log('uostype_field_initialize',$element.attr('id'),data);
}		