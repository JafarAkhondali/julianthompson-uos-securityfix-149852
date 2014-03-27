uos.types['field_boolean'] = {};

uos.types['field_boolean'].extends = null;

uos.types['field_boolean'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_field_boolean_initialize
	},

	deviceon : {
		title : 'On',
		icon : 'fa-power-off',	
	},
	
	deviceoff : {
		title : 'Off',
		icon : 'fa-power-off',				
	},
};


function uostype_field_boolean_initialize($element) {
	uos.log('uostype_field_boolean_initialize');
	//$element.find('.btn').button();
	//$element.css('border','1px solid red');
	//$element.find('.btn').css('border','1px solid red');
	$element.bind('click', function(event) {
		event.stopPropagation();
	});
	
	$switch = $element.find('.field-boolean-value');
	$switch.bootstrapSwitch();
}
