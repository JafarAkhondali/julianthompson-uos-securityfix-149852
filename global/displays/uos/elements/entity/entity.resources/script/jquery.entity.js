uos.types['entity'] = {};

uos.types['entity'].extends = null;

uos.types['entity'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_entity_initialize
	},
	
	reload : {
		title : 'Reload',
		icon : 'fa-refresh',				
	},
	
	add : {
		title : 'Add',		
		icon : 'fa-plus-circle'	
	},
	
	displayup : {
		title : 'Change Display',	
		icon : 'fa-caret-left',		
		handler: uostype_entity_display_down
	},
	
	displaydown : {
		title : 'Change Display',	
		icon : 'fa-caret-right',	
		handler: uostype_entity_display_up
	},	
	edit : {
		title : 'Edit',	
		icon : 'fa-pencil'					
	},
	remove : {
		title : 'Delete',			
		icon : 'fa-trash-o'					
	},
	save : {
		title : 'Save',		
		icon : 'fa-check'					
	},
	threed : {
		title : '3D display',
		icon : 'fa-globe',
		handler : uos_three
	}
};


function uostype_entity_initialize($element,data) {
	//$element.css('border','3px solid red');
	
	// bind some events
	$element.find('.uos-header').bind('click',function(event) {
		uostype_entity_header_click($element,event);
	});
	
	$element.find('.uos-header').bind('dblclick',function(event) {
		uostype_entity_header_dblclick($element,event);
	});
	
	uos.log('uostype_entity_initialize',$element.attr('id'),data);
	$element.removeClass('uos-uninitialized');
	$element.addClass('uos-initialized');
}		

function uostype_entity_header_click($element, event) {
  var elementdata = $element.data('uos-data');  
	//alert('clicked header');
	window.location = elementdata.clicktarget;
	event.stopPropagation();
}

function uostype_entity_header_dblclick($element, event) {
	alert('dblclick header');
	
	event.stopPropagation();
}

function uostype_entity_display_up($element, event) {

	var elementdata = $element.data('uos-data');
	//alert('display up');
	uos.log('uostype_entity_display_up',elementdata);
	
}

function uostype_entity_display_down($element, event) {
	uos.log('uostype_entity_display_down',$element.data('uos-data'));
}
	

function class_entity_post() {
	jQuery.ajax({
	  type: "POST",
	  url: '/global/uos.php',
	  context: document.body,
	  data: {
	  	selection : [4567898765,5645342341],
	  	universe : 'julian'
	  },
	  success: function(e) {
	  	uos.log(e);
	  },
	  //dataType: dataType
	});
}



