uos.displays['field'] = {};

uos.displays['field'].extends = uos.displays['entity'];

uos.displays['field'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_field_initialize
	},
	getvalue : {
		title : 'Get value',	
		icon : 'fa-wrench',
		handler : uostype_field_getvalue
	},
};

function uostype_field_initialize($element) {
	//$element.css('border','1px solid green');
	
  var elementdata = uos.getelementdata($element); 
	//uos.log('uostype_field_initialize',$element.attr('id'),elementdata);
}		


function uostype_field_getvalue($element) {
	return "GETVALUE WORKED";
}