//dragSrcEl = null;

var UOS_ELEMENT_CLASS = '.uos-element';
var UOS_ELEMENT_OBJECT = 'uos-entity';

uos = {};

uos.log = [];

uos.elements = [];

uos.libraries = {};

uos.types = [];

uos.types['global'] = {};

uos.settings = {};


//console.log(el.element);

//uos.types.entity = function() {};



uos.toggleinput = function() {
	jQuery('#input').toggleClass('opened');
}

uos.threeDim = function() {
	//uos.log(uos.getSelectedTitles());	
	//$elements = uos.getSelectedElements();
	$universeelement = jQuery(UOS_ELEMENT_CLASS).first();

	uos_three($universeelement);	
}


// when we've sorted actions remove this?
uos.global = {

	hasAction: function (actionname) {
		return (uos.global.actions[actionname])?true:false;
	},
	
	actions: {
		add : {
			title : 'Add',		
			icon : 'fa-plus-circle'	
		},
		trace : {
			title : 'Trace',
			icon : 'fa-sign-in',
			handler : uos.toggleinput
		},
		threed : {
			title : '3D',
			icon : 'fa-globe',
			handler : uos.threeDim
		},
		user : {
			title : 'User',
			icon : 'fa-user',
		},
	}
};




uos.initializeelement = function($element,data) {
	//uos.log('uos.initializeelement',$element.attr('id'),$element,data);
	uos.log(data);
	if (data) {
		$element.data('uos-data',data);	
	  var elementdata = $element.data('uos-data');
	  uos.log('uos-data', elementdata);
		uos.addBehaviours($element);
		var elementactions = $element.data('uos-actions');
		if (elementactions.init) {
			if (elementactions.init.handler) elementactions.init.handler($element);
		}
	}	
	$element.removeClass('uos-uninitialized');
	$element.addClass('uos-initialized');
};


uos.initalizeallelements = function() {
	jQuery('.uos-uninitialized').each(function(index) {
		var elementid = $(this).attr('id');
		var elementdata = uos.elements[elementid];
		uos.initializeelement($(this),elementdata);	
	});
}

uos.loadcontent = function($element,path) {

	var elementdata = $element.data('uos-data');
	var elementid = $element.attr('id');
	//elementdata.displaypaths
	//alert('display up');
	var selected = uos.isSelected($element);
	//uos.log('uostype_entity_display_up',elementdata.displaypaths,elementdata.display);
	//for (var i in ob) { // i will be "page1.html", "page2.html", etc...
  //  if (!ob.hasOwnProperty(i)) continue;
    // Do something with ob[i]
	//}
	//uostype_entity_get_display($element,elementdata.displaypaths[0]);
	$.getJSON( path, function( data ) {
  	//$( ".result" ).html( data );
		var content = data.content;
		var resources = data.resources;
		uos.log(data);
  	var $newelement = $(content);
  	var replacementid = $newelement.attr('id');
  	// remove data for current element
  	delete uos.elements[elementid];
  	//uos.elements[replacementid] = data.resources.json;
  	jQuery.extend(uos.elements, data.resources.json);
  	$element.replaceWith($newelement);
  	/*  	
		jQuery.each(uos.elements, function(index,elementdata) {
			var elementId = '#'+index;
			$element = jQuery(elementId);	
			if ($element.length>0) uos.processelement($element,elementdata);	
		});
		*/
  	uos.log('loadcontent',elementdata,elementid,replacementid,uos.elements,$newelement);

  	uos.initializeelement($newelement,uos.elements[replacementid]);
  	if (selected) $newelement.addClass('selected');
	});	
}

uos.addBehaviours = function($element) {
  var elementdata = $element.data('uos-data');
	var uostype = elementdata.type;
	var uosdisplay = elementdata.display;
	var uostypetree = elementdata.typetree;
	uos.log('uos.addBehaviors',elementdata);
  var elementactions = {};
	//for (in= 0; index < a.length; ++index) {
	for (var utindex = 0; utindex < uostypetree.length; ++utindex) {
		var searchtypename = uostypetree[utindex];
		//console.log(searchtypename);
		if(uos.types[searchtypename]) {
			var currenttype = uos.types[searchtypename];
			if (currenttype.actions) {
				uos.log('found actions for definition',uostype, searchtypename);
				for(var aindex in currenttype.actions) {
					// if we haven't defined the action already (overwritten)
					//console.log(elementactions);
					//elementactions['g'] = 'yes';
					if (!elementactions[aindex]) {
						elementactions[aindex] = currenttype.actions[aindex];
						//uos.log('found action definition', aindex, uostype, searchtypename);
					} 
				}
				//uos.log('addBehavioursx', uostype, elementactions);
			}
		}
	}

	// Clean - remove actions that aren't objects - overridden
	for(var aindex in elementactions) {
		if (!elementactions[aindex]) {
			delete elementactions[aindex];
		}
	}
	$element.data('uos-actions',elementactions);
	//uos.log('addBehaviours', uostype, elementactions);
};

uos.addActions = function($element) {

}

uos.log = function() {
	if (console && console.log) {
		console.log(arguments);
	}
};

uos.getChildElements = function($element) {
	return jQuery($element).find(UOS_ELEMENT_CLASS);	
}

uos.getAllChildEntities = function($element) {
	return jQuery($element).find(UOS_ELEMENT_CLASS + '.node');	
}

uos.selectElement = function($element, multiple) {
	uos.log($element.attr('title') + ' selected');
	$element.addClass('selected');
	uos.updateSelectedCount();
};

uos.deselectElement = function($element) {
	$element.removeClass('selected');
	uos.updateSelectedCount();
};

uos.deselectAllElements = function() {
	jQuery(UOS_ELEMENT_CLASS+'.selected').removeClass('selected');
	uos.updateSelectedCount();	
};

uos.getAllElements = function() {
	return jQuery(UOS_ELEMENT_CLASS);
};
	
uos.isSelected = function($element) {
	return $element.hasClass('selected');
};

uos.getSelectedElements = function() {
	return jQuery(UOS_ELEMENT_CLASS+'.selected');
};

uos.updateSelectedCount = function() {
	uos.buildToolbar();
	var count = uos.getSelectedElements().length;
	uos.log('updateSelectedCount', count);
	jQuery('#universe-selected-count').text(count);	
};

uos.getSelectedTitles = function() {
	var titles = [];
	jQuery(UOS_ELEMENT_CLASS+'.selected').each(function() {
		titles.push($(this).attr('title'));
	});
	return titles;
};
	
uos.buildToolbar = function() {
	uos.log('Build Toolbar');
	var toolbar = '';
	var actions = uos.getActions();	
	console.log(actions,'xxxxxxx');
	
	// clear existing actions from toolbar
	jQuery('#universe-actions').empty();
	
	// add relevant actions
	jQuery.each(actions, function(index,action) {
		//uos.log('xxx',action.title);
		var $control = jQuery('<i></i>');
		$control.attr('title',action.title);
		$control.addClass('fa');
		$control.click(function (event) {
			uos.toolbarAction(action,event);
		});
		$control.addClass(action.icon);
		$control = $control.wrap('<li></li>').parent();
		
		// append global tools
		jQuery('#universe-actions').append($control);
		//alert('xx');
	});
	jQuery('#universe-actions').append('<li class="km shift-keystatus">Shift</li><li class="km ctrl-keystatus">Ctrl</li><li class="km meta-keystatus">Meta</li><li class="km alt-keystatus">Alt</li>');
	//jQuery('#universe-actions').append('<li class="status-refreshing"><i class="fa fa-refresh fa-spin"></i></li>');
	//jQuery('#universe-actions').append('<li class="action-input"><i class="fa fa-sign-in"></i></li>');
};
	


uos.getActions = function (e) {
		var actions = null;
		// if no selection
		$selectedElements = uos.getSelectedElements();
			
		if ((elementcount = jQuery($selectedElements).length)>0) {
		
			jQuery($selectedElements).each(function(e) {
				var elementactions = $(this).data('uos-actions');
				console.log(elementactions);
				if (actions==null) {
					actions = elementactions;
				} else {
					actions = uos.getActionIntersection(actions, elementactions);
				}
			});
			jQuery('body').addClass('uos-toolbar-active');
		} else {
			actions = uos.global.actions;
			jQuery('body').removeClass('uos-toolbar-active');
		}
		//console.log(actions);
		return actions;
};

uos.getActionIntersection = function(d1,d2) {
	var matches = {};
  for(var aindex in d2) {
  	// if index in both - clone to matches
		if (d1[aindex]) {
			matches[aindex] = jQuery.extend({}, d2[aindex]);
			matches[aindex].handler = null;
		}
	}
	console.log(matches);
	return matches;
}

	
uos.getActionsOLD = function (e) {
		var actions = new Array();
		// if no selection
		$selectedElements = uos.getSelectedElements();
			
		if ((elementcount = jQuery($selectedElements).length)>0) {
			jQuery($selectedElements).each(function(e) {
			
			  var entity = $(this).data(UOS_ELEMENT_OBJECT);

			  if (entity && entity.actions) {
			  	uos.log(entity,entity.actions);
					jQuery.each(entity.actions, function(index,action) {
						//if (entity.hasAction(actionname)) {
							//uos.log('has');
							if (!actions.inArray(action)) {
								uos.log(action);
								actions.push(action);
							}
						//}
					});
			  }
			});
		} else {
			actions = uos.global.actions;
		}
		//console.log(actions);
		return actions;
};


uos.toolbarAction = function (action, event) {
	uos.log('Calling action : ', action);
	$elements = uos.getSelectedElements();
	if (action.handler) {
		action.handler($elements,event);
	} else {

	}

	//jQuery('div.node.selected').each(function(e) {
	//	
	//});
	//if (action.action) {
	//	action.action(jQuery('div.node.selected'));
	//} else {
	//	uos.log('Action not found.');
	//}
};


uos.getReadableFileSizeString = function(fileSizeInBytes) {	
	var i = -1;
	var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
	do {
	    fileSizeInBytes = fileSizeInBytes / 1024;
	    i++;
	} while (fileSizeInBytes > 1024);
	
	return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
};


uos.overlay = function(toggle) {
	if (toggle) {
		var $overlay = jQuery('<div class="uos-overlay"><i class="fa fa-refresh fa-spin"></i><div class="message">Updating</div></div>');
		jQuery('body').append($overlay);
	} else {
		jQuery('div.uos-overlay').remove();
	}
};


uos.load  = function() {
	uos.buildToolbar();
	filtermessages('jmt');
};



var dragSrcEl = null;

function handleDragStart(e) {

  //e.stopPropagation(); 
  // Target (this) element is the source node.
  //uos.log(typeof $(this).attr('data-downloadurl'));
	//jQuery(this).addClass('selected');
	if (!uos.isSelected(jQuery(this))) {
		handleNodeClick(this);
	}
	
  uos.log($(this).data('downloadurl'));
  uos.log($(this).data('guid'));
  uos.log($(this).attr('title'));
  var title = $(this).attr('title');
  var guid = $(this).data('guid');
  var elementdata = $(this).data('uos-elementdata');
  uos.log('uos-elementdata', elementdata.dragfile);
  //alert('here');
  
 	if (isShiftHeld() && elementdata.dragfile) {
		downloadurl = elementdata.dragfile;
	} else {
		downloadurl = elementdata.draglink;
	}
	e.dataTransfer.setData("DownloadURL",downloadurl);

  //this.style.opacity = '0.4';

  dragSrcEl = this;

  uos.log('dragstart',$(this).attr('title'));

  e.dataTransfer.effectAllowed = 'move';
  
  //$(e.currentTarget).addClass('dragging');
	//var clone = event.target.cloneNode(true);
  //event.target.parentNode.appendChild(clone);
  //event.target.ghostDragger = '<div>xxxx</div>';//SET A REFERENCE TO THE HELPER
  
  //$(clone).addClass('dragging');
  //$(this).addClass('dragging');
  //e.dataTransfer.setData('text/html', this.innerHTML);
  //var dragIcon = jQuery('<div>xxx</div>');
  uos.selectElement(jQuery(this),true);
  var iconcount = document.getElementById("universe-status-icon");
	var crt = iconcount.cloneNode(true);
	crt.id = "universe-drag-helper";
	$(crt).addClass('drag-helper');
  //crt.style.backgroundColor = "red";
  //crt.style.position = "absolute"; crt.style.top = "0px"; crt.style.left = "-100px";
  document.body.appendChild(crt);
  //var iconcount = jQuery('<div class="xxx">Test</div>');
  uos.log('iconcount');
 	e.dataTransfer.setDragImage(crt,-1,-1);
 	//e.dataTransfer.setDragImage(crt,-1,-1);
}

function handleDragOver(e) {
	//uos.log(dragContainsFiles(e)?"File":"Node");
	//uos.log(dragGetPayloadTypes(e));
  //if (e.stopPropagation) {
  //e.stopPropagation(); // Stops some browsers from redirecting.
  //}
	if (!uos.isSelected(jQuery(this))) {
  //if ($(this)[0] != $(dragSrcEl)[0]) {
	  if (e.preventDefault) {
	    e.preventDefault(); // Necessary. Allows us to drop.
	  }
	
	  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
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

function handleDragEnter(e) {
  // this / e.target is the current hover target.
  //uos.log('dragenter',$(this).attr('title'));
  //if (e.stopPropagation) {
    e.stopPropagation(); // Stops some browsers from redirecting.
  //}
  if ($(this)[0] != $(dragSrcEl)[0]) {
	  //this.classList.add('over');
	  e.preventDefault();
	  
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

function handleDragLeave(e) {
  // this / e.target is previous target element.
  //uos.log('leave',$(this).attr('title'));
  //this.classList.remove('over');  
  //$(this).removeClass('dragging-hover-target');
  //if (e.stopPropagation) {
  //  e.stopPropagation(); // Stops some browsers from redirecting.
  //}
  e.stopPropagation();
  $(this).removeClass('dragging-target');
  $(this).removeClass('dragging-noaccept');
}

function handleDrop(e) {
  // this/e.target is current target element.

  //uos.log('Dropped',e);
  uos.log('drop',$(this).attr('title'),$(e.target).attr('title'));
  $(this).removeClass('dragging');
  $(this).removeClass('dragging-hover-target');
  $(e.target).removeClass('dragging-target');

  if (e.stopPropagation) {
    e.stopPropagation(); // Stops some browsers from redirecting.
  }
  e.preventDefault();
  
  var data = e.dataTransfer.getData('text');
  uos.log(data);

  // Don't do anything if dropping the same column we're dragging.
  if (dragSrcEl != this) {
    // Set the source column's HTML to the HTML of the column we dropped on.
    //dragSrcEl.innerHTML = this.innerHTML;
    //this.innerHTML = e.dataTransfer.getData('text/html');
    // transfer data file?
    if (e.dataTransfer.files.length>0) {
    	uos.log('file drop');
    	//uos.log(e.dataTransfer.files);
    	var filenames = [];
    	for (var i = 0; i < e.dataTransfer.files.length; i++) {
      	uos.log("Dropped File : ", e.dataTransfer.files[i]);
      	filenames.push(e.dataTransfer.files[i].name + ' (' + uos.getReadableFileSizeString(e.dataTransfer.files[i].size) + ')');
      }
      
      $.growl.notice({ title : 'Dropped File(s)', message:  filenames.join(', ') + ' into ' + $(this).attr('title'), location : 'br'  });

    } else {
      //uos.log('node drop');
    	//uos.log(e.dataTransfer);
			var titles = uos.getSelectedTitles();
    	$.growl.notice({ title : 'Dropped Node(s)', message: 'Dropped : ' + titles.join(', ') + ' onto ' + $(this).attr('title'), location : 'br' });
    	uos.log('Dropped Node : ' + $(dragSrcEl).attr('title') + ' onto ' + $(this).attr('title'));
    	//uos.log(e.dataTransfer.getData('text/html'));
    }
  	$(this).removeClass('dragging-target');    
  }

  $(this).removeClass('dragging-noaccept');  
  //jQuery('#universe-drag-helper').remove();

  return false;
}

function handleDragEnd(e) {
  // this/e.target is the source node.
	uos.log('DragEnd');
  //$('.node').removeClass('over');
  jQuery('#universe-drag-helper').remove();
}

function handleKeyboardModifiers(e) {
	//uos.log(e);
	$('body').toggleClass('shift-pressed', e.shiftKey);
	$('body').toggleClass('meta-pressed', e.metaKey);
	$('body').toggleClass('ctrl-pressed', e.ctrlKey);
	$('body').toggleClass('alt-pressed', e.altKey);
}

function handleBackspace(e) {
	uos.log('Delete nodes');
  //if (e.stopPropagation) {
  //  e.stopPropagation(); // Stops some browsers from redirecting.
  //}
  e.preventDefault();
}

function handleTab(e) {
	if (e.metaKey) $('body').removeClass('meta-pressed');	
}

function isShiftHeld() {
	return $('body').hasClass('shift-pressed');
}

function isMetaHeld() {
	return $('body').hasClass('meta-pressed');
}

function isCtrlHeld() {
	return $('body').hasClass('ctrl-pressed');
}

function dragContainsFiles(event) {
	if (event.dataTransfer.types) {
		for (var i = 0; i < event.dataTransfer.types.length; i++) {
			if (event.dataTransfer.types[i] == "Files") {
				return true;
			}
		}
	}
	return false;
}

function dragGetPayloadTypes(e) {
	var types = new Array();
	var filelist = e.dataTransfer.files||e.target.files; // File list
	for (var i = 0; i < filelist.length; i++) {
			//if (event.dataTransfer.files[i].type == "Files") {
				types[i] = filelist[i].type;
			//}
	}
	return types;
}

function handleNodeClick(e) {
	//uos.log(e);
	if (isShiftHeld()) {
		uos.log('Shift select');
	} else {
		if (uos.isSelected(jQuery(this))) {
		//if (!e.ctrlKey) {
			if (!isMetaHeld()) {
				uos.deselectAllElements();
			}
			uos.deselectElement(jQuery(this),isMetaHeld());
		} else {
			if (!isMetaHeld()) {
				uos.deselectAllElements();
			}
			uos.selectElement(jQuery(this),isMetaHeld());
		}
	//jQuery(this).toggleClass('selected');
	}
	if (e.preventDefault) {
		e.preventDefault();
	}
	if (e.stopPropagation) {
		e.stopPropagation();
	}
	//return false;
}

function handleSave(e) {
  event.preventDefault();
  uos.log('saving?');
}

function handleCopy(e) {
  event.preventDefault();
  uos.log('copying?');
}

function saveChanges(e){ 
		e.preventDefault();
		e.stopPropagation();
		uos.log('Saved');
}


// Check for geolocation support

if (navigator.geolocation) {

// Use method getCurrentPosition to get coordinates

	navigator.geolocation.getCurrentPosition(function (position) {

		// Access them accordingly

		uos.log(position.coords.latitude + ", " + position.coords.longitude);
	});
}

Array.prototype.inArray = function(comparer) { 
    for(var i=0; i < this.length; i++) { 
        if(comparer == this[i]) return true; 
    }
    return false; 
}; 

function filtermessages(tag) {
  var count = 0;
  jQuery('#inputmessages > .array > table').each(function() {
		$tagfieldvalue = $(this).find('tr.key-tags .fieldvalue');
		//console.log($tagfieldvalue.text());
		//console.log($(this).attr('id'));
		if (tag!="" && $tagfieldvalue.text().indexOf(tag) < 0) {
			//$(this).css( "background-color", "red" );
			$(this).slideUp();
			count++;
		} else {
			$(this).slideDown();
			//$(this).css( "background-color", "green" );
		}
  });
  return count;
}


// remove when we've updated all the variables
//var universeos = uos;