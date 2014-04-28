uos.displays['node_device'] = {};

uos.displays['node_device'].extends = uos.displays['node'];

uos.displays['node_device'].actions = {

	deviceon : {
		title : 'On',
		icon : 'fa-power-off',
		handler : uostype_node_device_on	
	},
	
	deviceoff : {
		title : 'Off',
		icon : 'fa-power-off',
		handler : uostype_node_device_off					
	},
};


function uostype_node_device_initialize($element) {
	//$element.css('border','3px solid red');
	uostype_entity_initialize($element);
	/*
  var elementdata = uos.getelementdata($element); 
  var domelement = $element.get(0);
  
	$element.bind('click', function(event) {
		uostype_entity_event_click($(this),event);
	});
	
	//jquery binds don't work
  //$element.bind('dragstart', uostype_entity_event_dragstart);
	
  domelement.addEventListener("dragstart", uostype_entity_event_dragstart, false);  
	domelement.addEventListener('dragenter', uostype_entity_event_dragenter, false);
	domelement.addEventListener('dragover', uostype_entity_event_dragover, false);
	domelement.addEventListener('dragleave', uostype_entity_event_dragleave, false);
	domelement.addEventListener('dragend', uostype_entity_event_dragend, false);
	domelement.addEventListener('drop', uostype_entity_event_drop, false);

	// bind some events
	$elementheader = $element.find('.uos-header');
	
	if ($elementheader.length>0) {
	
		$elementheader.bind('click',function(event) {
			uostype_entity_event_header_click($element,event);
		});
		
		$elementheader.bind('dblclick',function(event) {
			uostype_entity_event_header_dblclick($element,event);
		});
	}
	
	uos.log('uostype_node_device_initialize',$element.attr('id'),elementdata);
	*/
}		

function uostype_node_device_on($element) {
  var elementdata = uos.getelementdata($element);
	uos.log('uostype_node_device_on',$element.attr('id'),elementdata);
}

function uostype_node_device_off($element) {
  var elementdata = uos.getelementdata($element);
	uos.log('uostype_node_device_off',$element.attr('id'),elementdata);
}

// functions for now should be named :
/// function uostype_ENTITYTYPE_hander($element,data)