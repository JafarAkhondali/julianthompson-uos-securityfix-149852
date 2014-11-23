uos.displays['field_boolean'] = uos.extenddisplay(uos.displays['field']);

uos.displays['field_boolean'].title = 'Boolean field';

uos.displays['field_boolean'].actions.init = {
	title : 'Initialise',	
	icon : 'fa-wrench',
	handler : uostype_field_boolean_initialize
};

uos.displays['field_boolean'].actions.deviceon = {
	title : 'On',
	icon : 'fa-power-off'	
};
	
uos.displays['field_boolean'].actions.deviceoff = {
	title : 'Off',
	icon : 'fa-power-off',
	handler : function($element) {
			uos.log('display.field_boolean.js.XXXXXXXXXXXXXX:poweroff',$element);
			$element.css('border','3px solid cyan');
	}			
};

uos.displays['field_boolean'].actions.getvalue.handler = function($element) {
	//console.log('BOOOOL');
  var elementdata = uos.getelementdata($element); 
	$switch = $element.find('.toggle-switch input[type=checkbox]');
	return ($switch.attr('checked')=='checked')?1:0;	
};



console.log('Included display \'field_boolean\'',uos.displays['field_boolean']);


function uostype_field_boolean_initialize($element) {
	//uos.log('uostype_field_boolean_initialize');
	//$element.find('.btn').button();
	//$element.css('border','1px solid red');
	//$element.find('.btn').css('border','1px solid red');
	$element.find('.uos-field-value-wrapper').bind('click', function(event) {
		event.stopPropagation();
	});
	
	$switch = $element.find('.toggle-switch input[type=checkbox]');
	$switch.bootstrapSwitch();
	//.on('switchChange.bootstrapSwitch', function(e,data) {
	//	alert(data.value);
	//
	
	//$switch.bootstrapSwitch('setReadOnly', true);
	
	//$switch.prop('disabled', true);
	$switch.on('switchChange.bootstrapSwitch', function(event, state) {
	  console.log(event); // jQuery event
	  console.log(state); // true | false
	  //$(this).prop('checked', false);
	  if (state) {
	      $(this).attr('checked','checked');
	  } else {
	      $(this).removeAttr('checked');
	  }
	  //console.log(this); // DOM element
	});
	//alert('yey');
}
