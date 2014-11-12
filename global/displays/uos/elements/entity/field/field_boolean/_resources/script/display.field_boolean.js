uos.displays['field_boolean'] = {};

uos.displays['field_boolean'].extends = null;

uos.displays['field_boolean'].actions = {

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
	//uos.log('uostype_field_boolean_initialize');
	//$element.find('.btn').button();
	//$element.css('border','1px solid red');
	//$element.find('.btn').css('border','1px solid red');
	//$element.bind('click', function(event) {
	//	event.stopPropagation();
	//});
	
	$switch = $element.find('.toggle-switch input[type=checkbox]');
	$switch.bootstrapSwitch();
	//.on('switchChange.bootstrapSwitch', function(e,data) {
	//	alert(data.value);
	//
	$switch.on('switchChange.bootstrapSwitch', function(event, state) {
	  console.log(event); // jQuery event
	  console.log(state); // true | false
	  //$(this).prop('checked', false);
	  if (state) {
	      $(this).attr('checked','checked');
	  } else {
	      $(this).removeAttr('checked');
	  }
	  console.log(this); // DOM element
	});

}
