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
		handler : uostype_entity_reload	
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


function uostype_entity_initialize($element) {
	//$element.css('border','3px solid red');
  var elementdata = uos.getelementdata($element); 
  var domelement = $element.get(0);
  
	$element.bind('click', function(event) {
		uostype_entity_event_click($(this),event);
	});
	
  domelement.addEventListener("dragstart", uostype_entity_event_dragstart, false);
  //$element.bind('dragstart', uostype_entity_event_dragstart);
  
	domelement.addEventListener('dragenter', handleDragEnter, false);
	domelement.addEventListener('dragover', handleDragOver, false);
	domelement.addEventListener('dragleave', handleDragLeave, false);
	domelement.addEventListener('dragend', handleDragEnd, false);
	domelement.addEventListener('drop', handleDrop, false);

	// bind some events
	$elementheader = $element.find('.uos-header');
	
	$elementheader.bind('click',function(event) {
		uostype_entity_event_header_click($element,event);
	});
	
	$elementheader.bind('dblclick',function(event) {
		uostype_entity_event_header_dblclick($element,event);
	});
	
	uos.log('uostype_entity_initialize',$element.attr('id'),elementdata);
}		



function uostype_entity_event_click($element,event) {

	var elementdata = uos.getelementdata($element);
	uos.log('uostype_entity_click',elementdata);
	event.preventDefault();
	event.stopPropagation();	

	if (isShiftHeld()) {
		//uos.extendSelection($element);
	} else {
		if (uos.isSelected($element)) {
			uos.log('element is currently selected');
			if (!isMetaHeld()) {
				uos.deselectAllElements();
			} 
			uos.deselectElement($element);
		} else {
			uos.log('element is not currently selected');
			if (!isMetaHeld()) {
				uos.deselectAllElements();
			}
			uos.selectElement($element,isMetaHeld());
		}
	}
	uos.updateSelectedCount();
	//uos.selectElement($element,isMetaHeld());
}



function uostype_entity_event_header_click($element, event) {
	var elementdata = uos.getelementdata($element);
	window.location = elementdata.clicktarget;
	event.preventDefault();
	event.stopPropagation();
}



function uostype_entity_event_dragstart(event) {
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
	
	//if (!uos.isSelected($element)) {
	//	uostype_entity_event_click($element,event);
	//}
  
  uos.selectElement($element,true);
	
	uos.log('uostype_entity_event_drag_start',$element.attr('title'),elementdata,event);
	
 	if (isShiftHeld() && elementdata.dragfile) {
		downloadurl = elementdata.dragfile;
	} else {
		downloadurl = elementdata.draglink;
	}

	event.dataTransfer.setData("DownloadURL",downloadurl);
	
  event.dataTransfer.effectAllowed = 'move';
  
  var iconcount = document.getElementById("universe-status-icon");
	var crt = iconcount.cloneNode(true);
	crt.id = "universe-drag-helper";
	$(crt).addClass('drag-helper');
  //crt.style.backgroundColor = "red";
  //crt.style.position = "absolute"; crt.style.top = "0px"; crt.style.left = "-100px";
  document.body.appendChild(crt);
  //var iconcount = jQuery('<div class="xxx">Test</div>');
  uos.log('iconcount');
 	event.dataTransfer.setDragImage(crt,-1,-1);
	event.stopPropagation(); 
}



function uostype_entity_event_header_dblclick($element, event) {
	alert('dblclick header');
	event.preventDefault();	
	event.stopPropagation();
}

function uostype_entity_display_up($element, event) {
	var elementdata = uos.getelementdata($element);
	var elementid = $element.attr('id');
	//elementdata.displaypaths
	//uos.log('displayup',elementdata);
	uos.loadcontent($element,"/8834323145.htmljson");
}


function uostype_entity_reload($element) {
	var elementdata = uos.getelementdata($element);
	uos.log('uostype_entity_reload',elementdata);
	uos.loadcontent($element,"/8834323145.htmljson");
}


function uostype_entity_display_down($element, event) {
	var elementdata = uos.getelementdata($element);
	uos.log('uostype_entity_display_down',elementdata);
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


function loadCSSifnotload(csspath) {
    var ss = document.styleSheets;
    for (var i = 0, max = ss.length; i < max; i++) {
        if (ss[i].href == "/path/to.css")
            return;
    }
    var link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = "/path/to.css";

    document.getElementsByTagName("head")[0].appendChild(link);
}

//if (!$("link[href='/path/to.css']").length)
//    $('<link href="/path/to.css" rel="stylesheet">').appendTo("head");



