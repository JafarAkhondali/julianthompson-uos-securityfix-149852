uos.displays['array.teaser.html'] = uos.extenddisplay(uos.displays['array.html']);

uos.displays['array.teaser.html'].title = 'Field';

uos.displays['array.teaser.html'].actions.init.handler = function ($element) {
	//$element.css('border','3px solid red');
  var elementdata = uos.getelementdata($element); 
  var domelement = $element.get(0);	
	uostype_array_add_insert_points($element);
	uostype_entity_addheader($element);
	uos.log('uostype_array_initialize',$element.attr('id'),elementdata);
}

console.log("Included display 'array.teaser.html'",uos.displays['array.teaser.html']);



function uostype_array_add_insert_points($element) {
	$element.prepend(uostype_array_create_insert_point($element.attr('id'),0));
	$element.children('div.entity').each(function(index,value) {
		$(this).after(uostype_array_create_insert_point($element.attr('id'),index+1));		
	});
}

function uostype_array_create_insert_point(elementid,insertpoint) {
	$insertpoint = jQuery('<div class="uos-insert-point" id="'+elementid+'-insertpoint-'+insertpoint+'" data-insertpoint="'+insertpoint+'">+</div>');
  var domelement = $insertpoint.get(0);
  
	//$insertpoint.bind('click', function(event) {
	//	alert('insertpoint clicked');
	//});
	
	$insertpoint.bind('dragenter', uostype_array_event_insert_point_dragenter);
	$insertpoint.bind('dragleave', uostype_array_event_insert_point_dragleave);
	$insertpoint.bind('dragover', uostype_array_event_insert_point_dragover);
	$insertpoint.bind('drop', uostype_array_event_insert_point_drop);
	
  //domelement.addEventListener("dragstart", uostype_entity_event_dragstart, false);  
	//domelement.addEventListener('dragenter', uostype_array_event_insert_point_dragenter, false);
	//domelement.addEventListener('dragover', uostype_entity_event_dragover, false);
	//domelement.addEventListener('dragleave', uostype_array_event_insert_point_dragleave, false);
	//domelement.addEventListener('dragend', uostype_entity_event_dragend, false);
	//domelement.addEventListener('drop', uostype_array_event_insert_point_drop, false);
	return $insertpoint;
}

function uostype_array_event_insert_point_dragenter(event) {
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
  event.stopPropagation(); // Stops some browsers from redirecting.
  event.preventDefault(); // allows us to drop;
  // cant drag into next space
	uos.log('uostype_array_event_insert_point_dragenter',$element.attr('id'),elementdata);
  
	$element.addClass('dragging-target');
}

function uostype_array_event_insert_point_dragover(event) {
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
  event.stopPropagation(); // Stops some browsers from redirecting.
  event.preventDefault(); // allows us to drop;
  // cant drag into next space
	uos.log('uostype_array_event_insert_point_dragover',$element.attr('id'),elementdata);
  
	$element.addClass('dragging-target');
}

function uostype_array_event_insert_point_dragleave(event) {
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
  event.stopPropagation(); // Stops some browsers from redirecting.
	$element.removeClass('dragging-target');
}

function uostype_array_event_insert_point_drop(event) {
	var $element = jQuery(this);
	//var elementdata = uos.getelementdata($element);
  event.stopPropagation(); // Stops some browsers from redirecting.
	$element.removeClass('dragging-target');
	uos.log('uostype_array_event_insert_point_drop',$element.attr('id'));
}