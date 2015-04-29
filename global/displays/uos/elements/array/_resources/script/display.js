uos.displays['array'] = uos.extenddisplay('element');


//uos.displays['array'] = {};

uos.displays['array'].title = 'List';

//uos.displays['array'].extends = null;

//uos.displays['array'].actions = {};

uos.displays['array'].actions.init = {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_array_initialize
};

uos.displays['array'].actions.threed = {
		title : '3D display',
		icon : 'fa-globe',
		handler : uos_three
};





function uostype_array_initialize($element) {
	//$element.css('border','3px solid red');
  var elementdata = uos.getelementdata($element); 
  var domelement = $element.get(0);	
	//$elementchildren = $element.find('div.uos-element-children > div.uos-type-entity').after('<div class="uos-insert-point">hello</div>');
	//alert('here');
	//uostype_entity_addheader($element);

	uos.log('uostype_array_initialize',$element.attr('id'),elementdata);
}		



