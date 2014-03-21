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




uos.initializeelement = function($element,elementdata) {

	if (elementdata) {
		elementdata.id = $element.attr('id');
		uos.setelementdata($element,elementdata);

	  //var elementdata = uos.getelementdata($element);
	  
	  //uos.log('uos-data', elementdata);
	  uos.logging = false;
		uos.addBehaviours($element);
		uos.logging = true;
		
		if (elementdata.actions.init) {
			if (elementdata.actions.init.handler) elementdata.actions.init.handler($element);
		}
	}	

	$element.removeClass('uos-uninitialized');
	$element.addClass('uos-initialized');
	
	//uos.logging = false;
	//uos.log('uos.initializeelementx',$element.attr('id'),uos.getelementdata($element));
	//uos.logging = false;
}


uos.setelementdata = function($element,data) {
	//jQuery.data($element,data);
	$element.data("uos",data);
}

uos.getelementdata = function($element) {
	//return jQuery.data($element);
	return $element.data("uos");
}


uos.initalizeallelements = function($content, elementsdata) {
	/*
	jQuery('.uos-uninitialized').each(function(index) {
		var elementid = $(this).attr('id');
		var elementdata = uos.elements[elementid];
		uos.initializeelement($(this),elementdata);	
	});
	*/
	uos.log(elementsdata);
	jQuery.each(elementsdata, function(index,elementdata) {
		var newelementid = '#'+index;
		$newelement = $content.find(newelementid);	
		if ($newelement.length>0) {
		  //$newelement.removeClass('uos-uninitialized').addClass('uos-initialized');
			uos.initializeelement($newelement,elementdata);	
			//uos.logging = true;
			//uos.log('intializeallelements',$newelement.attr('id'),uos.getelementdata($newelement));
			//uos.logging = false;
		}
		//uos.log(index, newelementid, elementdata);
	});
}

uos.loadcontent = function($element,path) {

	var elementdata = uos.getelementdata($element);
	var elementid = $element.attr('id');
	//elementdata.displaypaths
	uos.log('uos.loadcontent',path,elementid);
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
		var elementdata = data.elementdata;
		
		uos.log('uos.loadcontent.response',data);
		
		var $loadedcontent = $(content);
		
		jQuery.each(data.elementdata, function(index,elementdata) {
			var newelementid = '#'+index;
			$newelement = $loadedcontent.find(newelementid).addBack(newelementid);	
			if ($newelement.length>0) {
				//uos.log('uos.loadcontent.preinit',$newelement,newelementid,elementdata);
				uos.initializeelement($newelement,elementdata);
			}
		});
		
		//uos.log(uos.getelementdata($loadedcontent))
		
  	$element.replaceWith($loadedcontent);
	/*
	jQuery.each(uos.elements, function(index,elementdata) {
		var elementId = '#'+index;
		$element = jQuery(elementId);	
			
	});

  	var replacementid = $newelement.attr('id');
  	// remove data for current element
  	delete uos.elements[elementid];
  	//uos.elements[replacementid] = data.resources.json;
  	jQuery.extend(uos.elements, data.resources.json);

  	//uos.log('loadcontent',elementdata,elementid,replacementid,uos.elements,$loadedcontent);

  	//uos.initializeelement($loadedcontent,uos.elements[replacementid]);
  	*/
  	uos.log('uos.loadcontent.elementdata',uos.getelementdata($loadedcontent));
  	if (selected) $loadedcontent.addClass('selected');
	});	
}

uos.addBehaviours = function($element) {
  var elementdata = uos.getelementdata($element);
	var uostype = elementdata.type;
	var uosdisplay = elementdata.display;
	var uostypetree = elementdata.typetree;
	//uos.log('uos.addBehaviors',elementdata);
  var elementactions = {};
	//for (in= 0; index < a.length; ++index) {
	for (var utindex = 0; utindex < uostypetree.length; ++utindex) {
		var searchtypename = uostypetree[utindex];
		//console.log(searchtypename);
		if(uos.types[searchtypename]) {
			var currenttype = uos.types[searchtypename];
			if (currenttype.actions) {
				uos.log('found actions for definition',uostype, searchtypename, currenttype.actions);
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
	elementdata.actions = elementactions;
	//jQuery.data($element,'uosActions',elementactions);
	//$element.data('actions',);
	uos.log('addBehaviours.finished', uostype, elementactions);
};

uos.addActions = function($element) {

}

uos.log = function() {
	if (uos.logging && console && console.log) {
		//arguments[] = arguments.callee.caller.name;
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
	//uos.updateSelectedCount();
};

uos.deselectElement = function($element) {
	$element.removeClass('selected');
	uos.log('ds');
	//uos.updateSelectedCount();
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
	var count = uos.getSelectedElements().length;
	uos.log('updateSelectedCount', count);
	jQuery('#universe-selected-count').text(count);	
	uos.buildToolbar();
};

uos.getSelectedTitles = function() {
	var titles = [];
	jQuery(UOS_ELEMENT_CLASS+'.selected').each(function() {
		titles.push($(this).attr('title'));
	});
	return titles;
};

uos.isParentOf = function($testparent,$child) {
	return ($testparent.has($child)).length > 0;
}

uos.isParentOfSelected = function($testparent) {
	var childrenfound = 0;
	$selected = uos.getSelectedElements();
	$selected.each(function(index) {
		if (uos.isParentOf($testparent,$(this))) childrenfound++;
	});
	//uos.log('uos.isParentOfSelected',$testparent.attr('title'),childrenfound>0);
	return (childrenfound>0);
}

uos.isChildOfSelected = function($testchild) {
	var childrenfound = 0;
	$selected = uos.getSelectedElements();
	$selected.each(function(index) {
		if (uos.isParentOf($(this),$testchild)) childrenfound++;
	});
	//uos.log('uos.isChildOfSelected',$testchild.attr('title'),childrenfound>0);
	return (childrenfound>0);
}
	
uos.buildToolbar = function() {
	uos.log('Build Toolbar');
	var toolbar = '';
	
	// clear existing actions from toolbar
	jQuery('#universe-actions').empty();
	
	var actions = uos.getSelectedActions();	
	uos.log('Build Toolbar',actions);

	// add relevant actions
	jQuery.each(actions, function(index,action) {
		//uos.log('buildToolbar',action);
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
	});
	jQuery('#universe-actions').append('<li class="km shift-keystatus">Shift</li><li class="km ctrl-keystatus">Ctrl</li><li class="km meta-keystatus">Meta</li><li class="km alt-keystatus">Alt</li>');
	//jQuery('#universe-actions').append('<li class="status-refreshing"><i class="fa fa-refresh fa-spin"></i></li>');
	//jQuery('#universe-actions').append('<li class="action-input"><i class="fa fa-sign-in"></i></li>');
};
	

uos.getSelectedActionsold = function() {
		var actions = null;
		// if no selection
		var $selectedElements = uos.getSelectedElements();
		var elementcount = $selectedElements.length;
		uos.log('uos.getSelectedActions');			
		// if no elements selected add global actions
		if (elementcount > 0) {
			$selectedElements.each(function(index) {
				var $element = $(this);
				var elementdata = uos.getelementdata($element);
				uos.log('uos.getSelectedActions',$element.attr('id'),elementdata.actions);
			});
		} else {
			actions = uos.global.actions;
		}
		//console.log(actions);
		return (actions==null)?[]:actions;

}


uos.getSelectedActions = function() {
		var actions = null;
		// if no selection
		var $selectedElements = uos.getSelectedElements();
		var elementcount = $selectedElements.length;
		console.log('uos.getSelectedActions');			
		// if no elements selected add global actions
		if (elementcount > 0) {
		
			$selectedElements.each(function(index) {
				var $element = $(this);
				var elementdata = uos.getelementdata($element);
				console.log('uos.getSelectedActions',$element.attr('id'),elementdata,elementdata.actions);
				if (elementdata && elementdata.actions) {
					if (actions==null) {
						actions = elementdata.actions;
					} else {
						actions = uos.getActionIntersection(actions, elementdata.actions);
					}
				}
			});
			jQuery('body').addClass('uos-toolbar-active');
		} else {
			actions = uos.global.actions;
			jQuery('body').removeClass('uos-toolbar-active');
		}
		//console.log(actions);
		return (actions==null)?[]:actions;
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

	$elements = uos.getSelectedElements();
	uos.log('Calling action : ' + action.title, $elements);	
	if (action.handler) {
		$elements.each(function(index) {
			action.handler($elements,event);
		});
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


uos.initialize  = function() {


	//$(document).bind('keydown', 'Ctrl+s', saveChanges);
	
	$(document).bind('keyup keydown', handleKeyboardModifiers);
	
	$(document).bind('keydown', 'backspace', handleBackspace);
	
	$(document).bind('keydown', 'meta+s', saveChanges);
	
	$(document).bind('keydown', 'tab', handleTab);
	
	$(document).bind('keydown', 'ctrl+s', saveChanges);

	$(document).bind('keydown', 'meta+c', handleCopy);
	
	$(document).bind('keydown', 'ctrl+c', handleCopy);
	
  $(window).focus(function() {
      uos.log('Focus');
  });

  $(window).blur(function() {
      uos.log('Blur');
      $('body').removeClass('meta-pressed');
  });
  
 	$(window).resize(function() {
      uos.log('Resize');
  });

	if (uos.elements) {
		uos.initalizeallelements(jQuery('body'),uos.elements);
	  uos.log('uos.initialize completed');
		uos.checkelementdata();	  

	}	

	uos.buildToolbar();
	//filtermessages('jmt');
};



uos.checkelementdata  = function() {
	uos.logging = true;
	jQuery.each(uos.elements, function(index,elementdata) {
		var newelementid = '#'+index;
		$newelement = jQuery('body').find(newelementid);	
		if ($newelement.length>0) {
			uos.log('afterinit',$newelement.attr("id"),$newelement.attr("title"),uos.getelementdata($newelement));
		}
		//uos.log(index, newelementid, elementdata);
	});
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
/*
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
*/
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