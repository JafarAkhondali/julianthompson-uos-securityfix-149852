


uos.displays['entity'] = {};

uos.displays['entity'].title = 'Entity';

uos.displays['entity'].extends = null;

uos.displays['entity'].actions = {};


uos.displays['entity'].actions.init = {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_entity_initialize
};

	
uos.displays['entity'].actions.reload = {
		title : 'Reload',
		icon : 'fa-refresh',
		handler : uostype_entity_reload	
};

	
uos.displays['entity'].actions.add = {
		title : 'Add',		
		icon : 'fa-plus-circle',
		handler : uostype_entity_add	
};

	
uos.displays['entity'].actions.displayup = {
		title : 'Change Display',	
		icon : 'fa-caret-left',		
		handler: uostype_entity_display_down
};

	
uos.displays['entity'].actions.displaydown = {
		title : 'Change Display',	
		icon : 'fa-caret-right',	
		handler: uostype_entity_display_up
};

	
uos.displays['entity'].actions.update = {
		title : 'Edit',	
		icon : 'fa-pencil',
		handler: uostype_entity_update						
};


uos.displays['entity'].actions.destroy = {
		title : 'Destroy',			
		icon : 'fa-trash-o',
		handler: uostype_entity_destroy				
};


uos.displays['entity'].actions.save = {
		title : 'Save',		
		icon : 'fa-check'					
};


uos.displays['entity'].actions.threed = {
		title : '3D display',
		icon : 'fa-globe',
		handler : uos_three
};

	
uos.displays['entity'].actions.test = {
		title : 'Test',
		icon : 'fa-globe',
		handler : uostype_entity_testaction
};


console.log('Included display \'entity\'',uos.displays['entity']);


function uostype_entity_add($element) {
	//jQuery('<p>I want banana!</p>').modal();
	//BootstrapDialog.alert('I want banana!');
	//var elementdata = uos.getelementdata($element);
	uos.post($element,'add',{
		//debugrequest : true
	});
}

function uostype_entity_testaction($element) {
	//jQuery('<p>I want banana!</p>').modal();
	//BootstrapDialog.alert('I want banana!');
	//var elementdata = uos.getelementdata($element);
	uos.post($element,'test',{
		//debugrequest : true
	});
}

function uostype_entity_destroy($element) {
	uos.post($element,'destroy',{
		//debugrequest : true
	});
}

function uostype_entity_update($element) {
	uos.post($element,'update',{
		//debugrequest : true
	});	
}


function uostype_entity_initialize($element) {
	//$element.css('border','3px solid red');
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
	$elementheader = $element.children('.uos-header');
	
	if ($elementheader.length>0) {
		$elementheader.bind('click',function(event) {
			uostype_entity_event_header_click($element,event);
		});
		
		$elementheader.bind('dblclick',function(event) {
			uostype_entity_event_header_dblclick($element,event);
		});
	}
	
	$element.find('form').click(function(event) { 
		event.stopPropagation();
	});
	

	uostype_entity_processform($element);
	uos.log('uostype_entity_initialize',$element.attr('id'),elementdata);
}		

function uostype_entity_processform($element) {
	$element.find('form').submit(function(event) { 
		event.preventDefault();
		$form = jQuery(this);
		var parameters = {};
		
		var files = new Array();
		
		var action = {};
		
		action['action']='unknown';
		$targetelement = $element;
		//console.log('here');		
		
		$element.find('.uos-element.field').each(function() {
			$field = jQuery(this);
			$field.css('border','1px solid red');
			var elementdata = uos.getelementdata($field); 
			var fieldvalue = uos.elementAction($field,'getvalue');
			var keyname = elementdata.fieldkey;
			//console.log($field,fieldvalue)
			if (fieldvalue!=undefined) {
			
				var systemproperties = ['target','action','sourceid'];
				if (systemproperties.indexOf(keyname)<0) {
					parameters[keyname] = fieldvalue;
					uos.log('uostype_entity_processform:param',elementdata.fieldkey,elementdata,fieldvalue);
				} else {
					action[keyname] = fieldvalue;
					uos.log('uostype_entity_processform:data',elementdata.fieldkey,fieldvalue);
				}
			}
			//uos.log('uostype_entity_initialize:data',keyname,keyvalue);		
		});
		/*
		$form.find('input[type=text],input[type=hidden],input[type=checkbox],textarea,select').each(function() {
			//jQuery(this).css('border','1px solid red');
			var keyname = jQuery(this).attr('name');
			
			var keyvalue = jQuery(this).val();
			var systemproperties = ['target','action','sourceid'];
			if (systemproperties.indexOf(keyname)<0) {
				parameters[keyname] = keyvalue;
				uos.log('uostype_entity_processform:param',keyname,keyvalue);
			} else {
				action[keyname] = keyvalue;
				uos.log('uostype_entity_processform:data',keyname,keyvalue);
			}
			
		});
		*/
		$form.find('input[type=file]').each(function() {
			var keyname = jQuery(this).attr('name');
			files = this.files;
			//var keyvalue = jQuery(this).val();	
			//uos.log(this,jQuery(this));	
		});
		
		if (action['sourceid']) {
			$targetelement = jQuery(action.sourceid);
		}
		
		uos.log('uostype_entity_processform:parameters',action, parameters, files);
	uos.post($targetelement,action.action,parameters,files);
		//alert('done');
	});
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
	uos.updateSelectedInfo();
	//uos.selectElement($element,isMetaHeld());
}



function uostype_entity_event_header_click($element, event) {
	var elementdata = uos.getelementdata($element);
	//console.log(elementdata);
	event.preventDefault();
	event.stopPropagation();	
	window.location = elementdata.clicktarget;

}



function uostype_entity_event_dragstart(event) {
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);

	//if (!uos.isSelected($element)) {
	//	uostype_entity_event_click($element,event);
	//}
  $element.trigger('click');
  uos.selectElement($element,true);
  uos.updateSelectedInfo();
  
	jQuery('body').addClass('uos-dragging-entity');	
	
	uos.log('uostype_entity_event_drag_start',$element.attr('title'),elementdata,event);
	
 	if (isShiftHeld() && elementdata.dragfile) {
		downloadurl = elementdata.dragfile;
	} else {
		downloadurl = elementdata.draglink;
	}
	uos.log(downloadurl);

	event.dataTransfer.setData("application/uos",'12345678909876');
	event.dataTransfer.setData("DownloadURL",downloadurl);
  event.dataTransfer.effectAllowed = 'move';
 
  var draghelper = uos.buildDragHelper();
	//container").css("left"))));
  //document.body.appendChild(draghelper);
 	event.dataTransfer.setDragImage(draghelper,-1,-1);
 	
 	setTimeout(function(){
    $("#uos-drag-helper-container").hide();
  },0);
  //$("#uos-drag-helper-container").css("left",-50);//(parseInt($("#uos-drag-helper-
	event.stopPropagation(); 
}



function uostype_entity_event_dragover(event) {

	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
	//uos.log(dragContainsFiles(e)?"File":"Node");
	//uos.log(dragGetPayloadTypes(e));
  //if (e.stopPropagation) {
  event.stopPropagation(); // Stops some browsers from redirecting.
  
	//uos.log('uostype_entity_event_dragover',$element.attr('title'),elementdata,event);
  //}
	if (!uos.isSelected($element) && !uos.isParentOfSelected($element) && !uos.isChildOfSelected($element)) {
  //if ($(this)[0] != $(dragSrcEl)[0]) {
	  if (event.preventDefault) {
	    event.preventDefault(); // Necessary. Allows us to drop.
	  }
	
	  event.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
	  // dummy test - call willAccept(e)
	  if ($(this).data('type')=='node_file') {
	  	$(this).addClass('dragging-noaccept');
	  } else {
	  //if ($(this)[0] != $(e.target)[0]) {
	  	//$(this).addClass('dragging-hover-target');
	  	$(this).addClass('dragging-target');
	  }
	  //$(this).addClass('dragging-target');
	  return false;
  }
}



function uostype_entity_event_dragenter(event) {
  // this / e.target is the current hover target.
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
	
  //uos.log('dragenter',$(this).attr('title'));
  //if (e.stopPropagation) {
  event.stopPropagation(); // Stops some browsers from redirecting.
  //}

	//uos.log(uostype_entity_identify_content(event));
	
	if (!uos.isSelected($element) && !uos.isParentOfSelected($element) && !uos.isChildOfSelected($element)) {
  //if ($(this)[0] != $(dragSrcEl)[0]) {
	  //this.classList.add('over');
	  event.preventDefault();
	  if (uostype_entity_accept_selection($element)) {
	  
	  }
	  // dummy test - call willAccept(e)
	  if ($(this).data('type')=='node_file') {
	  	$(this).addClass('dragging-noaccept');
	  } else {
	  //if ($(this)[0] != $(e.target)[0]) {
	  	//$(this).addClass('dragging-hover-target');
	  	$(this).addClass('dragging-target');
	  }
	  return true;
  }
}

function uostype_entity_accept_selection($element) {

	var elementdata = uos.getelementdata($element);
	// will elementdata.type
	//$selected = uos.getSelectedElements();
	var selectiontypes = uos.getSelectedTypes();
	
	// test case - if selection contains a message we wont accept it
	//if (selectiontypes.indexOf("node_message")>0) {
	// test case - if first item is node_message ?!!
	//uos.log('uos.getSelectedTypes gave me',typeof(selectiontypes));
	//if (selectiontypes[0]=='node_message') {
	//	uos.log('fuck off with ya fancy content types - I dont want your filthy messages');
	//	$element.addClass('uos-blocking');
	//} else {
	//	uos.log('good! your selection',selectiontypes,'doesnt contain those damn messages. phew!');	
	//}
	
	uos.log('uostype_entity_accept_selection:',$element.attr('title'),elementdata.type, selectiontypes);
}



function uostype_entity_event_dragleave(event) {
  // this / e.target is previous target element.
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
  //uos.log('leave',$(this).attr('title'));
  //this.classList.remove('over');  
  //$(this).removeClass('dragging-hover-target');
  //if (e.stopPropagation) {
  //  e.stopPropagation(); // Stops some browsers from redirecting.
  //}
  event.stopPropagation();
  $element.removeClass('dragging-target');
  $element.removeClass('dragging-noaccept');
}


function uostype_entity_event_drop(event) {
  // this/e.target is current target element.
	var $element = jQuery(this);
	var elementdata = uos.getelementdata($element);
	
	jQuery('body').removeClass('uos-dragging-entity');
  $element.removeClass('dragging');
  $element.removeClass('dragging-hover-target');
  $(event.target).removeClass('dragging-target');
	
  uos.log('uostype_entity_event_drop',$element.attr('title'),$(event.target).attr('title'));

  if (event.stopPropagation) {
    event.stopPropagation(); // Stops some browsers from redirecting.
  }
  event.preventDefault();
  
  var data = event.dataTransfer.getData('text');

	switch(uostype_entity_identify_content(event)) {

		//case 'text/uri-list' :
			//$.growl.notice({ title : 'Dropped file(s)', message: 'Dropped : multiple files onto ' + $element.attr('title'), location : 'br' });
			//uos.dropfiles($element,event.dataTransfer.files);		
		//break;

		// if we drop files		
		case 'Files' :
			//uos.dropfiles($element,event.dataTransfer.files);	
	    uos.post($element,'dropfiles',{
	    	content: uos.getSelectedGuids()
	    },event.dataTransfer.files);
		break;
		
		// if we drop entities
		case 'application/uos' :
	  	if (!uos.isSelected($element)) {
				var titles = uos.getSelectedTitles();
	    	uos.notification({ title : 'Dropped Node(s)', message: 'Dropped : ' + titles.join(', ') + ' onto ' + $element.attr('title')});
	    	uos.post($element,'dropentity',{
	    		content: uos.getSelectedGuids()
	    	});
	    	uos.log('uostype_entity_event_drop',elementdata.guid);
	    }  
		break;
		
    // if we drop content
		default: 
	    //uos.log(event.dataTransfer);
	    //uos.log(event.dataTransfer.types); 
	    //uos.log(event.dataTransfer.getData('text/plain'));//.match(/src\s*=\s*"(.+?)"/));
	    //uos.log(event.dataTransfer.getData('text/html'));//.match(/src\s*=\s*"(.+?)"/));
	    var content = {};
	    for (var typeindex = 0; typeindex < event.dataTransfer.types.length; typeindex++) {
	    	var mimetypename = event.dataTransfer.types[typeindex];
	    	//content.push({
	    	//	mimetype : mimetypename,
	    	//	content : event.dataTransfer.getData(mimetypename)
	    	//});
	    	content[mimetypename] = event.dataTransfer.getData(mimetypename);
	    }
	    uos.log('uostype_entity_event_drop',content);
	    var contenttext = event.dataTransfer.getData('text/plain');
	    var contenttrimmed = '"' + ((contenttext.length>100) ? contenttext.substring(0, 100) + '...' : contenttext) + '"';
	    if (contenttext=='') contenttrimmed = 'content'
	    uos.notification({ title : 'Dropped Content', message: 'Dropped : ' + contenttrimmed + ' onto ' + $element.attr('title')}); 
    	uos.post($element, 'dropcontent', {
    			content: content,
    	});  
		break;
	}
  
  $element.removeClass('dragging-target'); 
  $element.removeClass('dragging-noaccept');  
  $element.removeClass('uos-blocking');
  //jQuery('#universe-drag-helper').remove();

  return false;
}

function uostype_entity_identify_content(event) {
	uos.log('uostype_entity_identifycontent : ' + event.dataTransfer.types[0], event.dataTransfer.types); 
  return event.dataTransfer.types[0]; 
}

function uostype_entity_event_dragend(event) {
  // this/e.target is the source node.
	uos.log('DragEnd');
	jQuery('body').removeClass('uos-dragging-entity');
  //$('.node').removeClass('over');
  jQuery('#universe-drag-helper').remove();
  jQuery('#uos-drag-helper-container').remove();
	uos.getAllElements().removeClass('uos-blocking');
}


function uostype_entity_event_header_dblclick($element, event) {
	alert('dblclick header');
	event.preventDefault();	
	event.stopPropagation();
}


function uostype_entity_display_change($element, increment) {
	var elementdata = uos.getelementdata($element);
	var elementid = $element.attr('id');
	//elementdata.displaypaths
	var displays = Object.values(elementdata.displays);
	var displayindex = jQuery.inArray(elementdata.activedisplay,displays);
	var displaycount = displays.length;
	displayindex+=increment;
	if (displayindex>=displaycount) displayindex = 0;
	if (displayindex<0) displayindex = (displaycount-1);
	var newdisplay = displays[displayindex];
	uos.log('displaychange',elementdata.activedisplay,displays,displayindex,displaycount, newdisplay,elementdata);
  //var $requestelement = jQuery('<div class="uos-request"/>');
  //$requestelement.append('<h1><i class="fa fa-cog fa-spin"></i> Adding files</h1>');
	uos.loadcontent($element,"/"+elementdata.guid+".view.uosio?display="+newdisplay);
}


function uostype_entity_reload($element) {
	var elementdata = uos.getelementdata($element);
	uos.log('uostype_entity_reload',elementdata);
	uos.loadcontent($element,"/"+elementdata.guid+".view.uosio?display="+elementdata.activedisplay);
}

function uostype_entity_display_up($element, event) {
	uostype_entity_display_change($element, 1);
}


function uostype_entity_display_down($element, event) {
	uostype_entity_display_change($element, -1)
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
	  	uos.log('class_entity_post',e);
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



