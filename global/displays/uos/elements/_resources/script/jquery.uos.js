//dragSrcEl = null;

var UOS_ELEMENT_CLASS = '.uos-element';
var UOS_ELEMENT_OBJECT = 'uos-entity';

uos = {};

uos.log = [];

uos.elements = [];

uos.libraries = {};

uos.displays = [];

uos.displays['global'] = {};

uos.settings = {};

uos.session = {};


//console.log(el.element);

//uos.displays.entity = function() {};



uos.toggleinput = function() {
	jQuery('#input').toggleClass('opened');
}

uos.threeDim = function() {
	//uos.log(uos.getSelectedTitles());	
	//$elements = uos.getSelectedElements();
	$universeelement = jQuery(UOS_ELEMENT_CLASS).first();

	uos_three($universeelement);	
}

uos.addcontent = function() {
	//e.preventDefault();
  //var url = $(this).attr('href')
  //var url = "www.google.com";
  //var modal_id = $(this).attr('data-target');
  alert('gere');
  //$.get(url, function(data) {
  //    $(data).modal();
  //});
}


// when we've sorted actions remove this?
uos.global = {

	hasAction: function (actionname) {
		return (uos.global.actions[actionname])?true:false;
	},
	
	actions: {
		add : {
			title : 'Add content',		
			icon : 'fa-plus-circle',
			handler : uos.addcontent
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
	  //uos.logging = false;
		uos.addBehaviours($element);
		//uos.logging = true;
		
		if (elementdata.actions.init) {
			if (elementdata.actions.init.handler) elementdata.actions.init.handler($element);
		}
	}	

	$element.removeClass('uos-uninitialized');
	$element.addClass('uos-initialized');
	
	//uos.logging = false;
	//uos.log('uos.initializeelement',$element.attr('id'),uos.getelementdata($element));
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
	//uos.log('uos.initalizeallelements',elementsdata);
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
  $element.addClass('uos-processing');
  var $requestelement = jQuery('<div class="uos-request"/>');
  $requestelement.append('<h1><i class="fa fa-cog fa-spin"></i> Updating display</h1>');      
  $element.append($requestelement);  
	//uostype_entity_get_display($element,elementdata.displaypaths[0]);
	setTimeout(function () {

		$.getJSON( path, function( data ) {
	  	//$( ".result" ).html( data );
			var content = data.content;
			var resources = data.resources;
			var elementdata = data.elementdata;
			
			uos.log('uos.loadcontent.response',content, resources, elementdata);
			
			var $loadedcontent = $(content);
			
			//load scripts and stylesheets
			//var scriptloads = uos.loadscripts(data.resources.script);
			//uos.loadstyles(data.resources.style);
			
			
			// when scripts are loaded
			jQuery.getScript(data.resources.script,function(){
			//$.when.apply(scriptloads).done(function(){
				//uos.log('loadedscript',data.elementdata);
				//uos.log(uos);
				

				// process elements				
				jQuery.each(data.elementdata, function(index,elementdata) {
					var newelementid = '#'+index;
					$newelement = $loadedcontent.find(newelementid).addBack(newelementid);	
					if ($newelement.length>0) {
						//uos.log('uos.loadcontent.preinit',$newelement,newelementid,elementdata);
						//uos.log('$newelement',$newelement.html());
						uos.initializeelement($newelement,elementdata);
					}
				});				
				
				$element.replaceWith($loadedcontent);				

	  		uos.log('uos.loadcontent.elementdata',uos.getelementdata($newelement));
	  		if (selected) $loadedcontent.addClass('selected');	
	  		uos.buildToolbar();			
			});			
		});	
  }, 500);

}

uos.addBehaviours = function($element) {
  var elementdata = uos.getelementdata($element);
	var uostype = elementdata.type;
	var uosdisplay = elementdata.activedisplay;
	var uostypetree = elementdata.typetree;
	var uosdisplaykey = elementdata.displaykey;
	//uos.log('uos.addBehaviors',elementdata);
  var elementactions = {};
  //console.log('addBehaviours', elementdata, uos.displays, uosdisplaykey);
	//for (in= 0; index < a.length; ++index) {
	//if (uos.displays[uosdisplaykey]) {
	//	console.log('found : '+uosdisplaykey);
	//}
	//for (var utindex = uostypetree.length-1; utindex >=0; utindex--) {
	for (var utindex = 0; utindex < uostypetree.length; ++utindex) {
		//uos.log('searching for '+searchtypename)
	//for (var utindex = 0; utindex < uostypetree.length; ++utindex) {
		var searchtypename = uostypetree[utindex];
		var searchdisplay = searchtypename + '.' + uosdisplay;
		//uos.log('uos.addBehaviours:search',searchdisplay);
		if(uos.displays[searchdisplay]) {
			//uos.log('found:',searchdisplay);
			elementactions = uos.displays[searchdisplay].actions;
		} else {
			//uos.log('no actions found:',searchdisplay);		
		}

		if(uos.displays[searchtypename]) {
	  	//console.log('found :',searchtypename,uos.displays[searchtypename]);
			var currenttype = uos.displays[searchtypename];
			if (currenttype.actions) {
				//uos.log('found actions for definition',uostype, searchtypename, currenttype.actions);
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
		} else {
	  	//console.log('not found :',searchtypename,uos.displays[searchtypename]);		
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
	//uos.log('addBehaviours.finished', uostype, elementactions);
};


uos.loadstyles = function(styles) {
	for (index = 0; index < styles.length; ++index) {
	    console.log('uos.loadstyles',styles[index]);
	}
	//$('<link/>', {rel: 'stylesheet', href: 'myHref'}).appendTo('head');
};	

uos.addActions = function($element) {

};

uos.log = function() {
	if (uos.logging && console && console.log) {
		//if (arguments.callee.caller.name) arguments.push(arguments.callee.caller.name);
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
	var $selectedelements = uos.getSelectedElements();
	var selectedtypes = uos.getSelectedTypes();
	
	var count = $selectedelements.length;


	$("#uos-entity-icon").removeClass (function (index, css) {
  	return (css.match (/(^|\s)fa-\S+/g) || []).join(' ');
	});	
	if (count<1) {
		jQuery('#uos-entity-title').text('Nothing selected');	
		jQuery('#uos-entity-type').text('');		
	} else {
		$firstelement = $selectedelements.first();
		$elementdata = uos.getelementdata($firstelement);	
		if (count==1) {	
			uos.log('updateSelectedCount',$elementdata,selectedtypes);
			jQuery('#uos-entity-title').text($firstelement.attr('title'));	
			jQuery('#uos-entity-type').text($elementdata.typeinfo.title);	
			jQuery('#uos-entity-icon').addClass('fa-'+$elementdata.typeinfo.icon);	
		} else {
			jQuery('#uos-entity-title').text('Multiple entities');	
			if (selectedtypes.length>1) {
				jQuery('#uos-entity-type').text('Mixed');		
			}	else {
				jQuery('#uos-entity-type').text($elementdata.typeinfo.title);		
			}
		}	
	}
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

uos.getSelectedGuids = function() {
	var guids = [];
	jQuery(UOS_ELEMENT_CLASS+'.selected').each(function() {
		var elementdata = uos.getelementdata(jQuery(this));
		guids.push(elementdata.guid);
	});
	return guids;
};

uos.getSelectedTypes = function() {
	var typearray = [];
	
	var $elements = uos.getSelectedElements();
	
	for (index = 0; index < $elements.length; ++index) {
		//$element = $(this);
		var $element = jQuery($elements[index]);
		var elementdata = uos.getelementdata($element);
		//uos.log(elementdata.type);
		//typearray[elementdata.type] = true;
		typearray.push(elementdata.type);
	}
	//uos.log(typearray);
	return unique(typearray);
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
	var response = null;
	$elements = uos.getSelectedElements();
	uos.log('Calling action : ' + action.title, $elements);	
	if (action.handler) {
		$elements.each(function(index) {
			response = action.handler($elements,event);
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
	return response;
};

uos.elementAction = function ($element,actionname) {
	var elementdata = uos.getelementdata($element);
	if (elementdata.actions[actionname] && elementdata.actions[actionname].handler) {
    var args = []; // empty array
    // copy all other arguments we want to "pass through" 
    for(var i = 2; i < arguments.length; i++) {
        args.push(arguments[i]);
    }
    return elementdata.actions[actionname].handler.apply($element, args);
	}
}


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

uos.buildDragHelper = function() {
  var iconcount = document.getElementById("uos-status-icon");
	var draghelper = iconcount.cloneNode(true);
	draghelper.id = "universe-drag-helper";
	$(draghelper).addClass('drag-helper');
  //crt.style.backgroundColor = "red";
  //crt.style.position = "absolute"; crt.style.top = "0px"; crt.style.left = "-100px";
  $('body').append('<div id="uos-drag-helper-container">');
  $('#uos-drag-helper-container').append(draghelper);
  return draghelper;
}


uos.notification = function(options) {


	var Notification = window.Notification || window.mozNotification || window.webkitNotification;
	
	if (Notification) {

		Notification.requestPermission(function (permission) {
			// console.log(permission);
		});
	
		var instance = new Notification(
			options.title, {
					body: options.message
			}
		);
	
		instance.onclick = function () {
			// Something to do
		};
		
		instance.onerror = function () {
			// Something to do
		};
		
		instance.onshow = function () {
		   var $this = this;
       setTimeout(function(){
          $this.close();
       }, 3000);
		};
		
		instance.onclose = function () {
			// Something to do
		};
	
	} else if ($.growl) {
		$.growl.notice({ 
			title : options.title, 
			message:  options.message, 
			location : 'br'  
		});
	}

}


uos.initialize = function() {
	
	$(document).bind('keyup keydown', handleKeyboardModifiers);
	
	$(document).bind('keydown', 'backspace', handleBackspace);
	
	$(document).bind('keydown', 'meta+s', saveChanges);
	
	$(document).bind('keydown', 'tab', handleTab);
	
	$(document).bind('keydown', 'ctrl+s', saveChanges);

	$(document).bind('keydown', 'meta+c', handleCopy);
	
	$(document).bind('keydown', 'ctrl+c', handleCopy);
	
  $(window).focus(function() {
      //uos.log('Focus');
  });

  $(window).blur(function() {
      //uos.log('Blur');
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
	
	uos.notification({
		title : 'UniverseOS',
		message : 'Initialized'
	});
	
	uos.getcurrentlocation();
	//filtermessages('jmt');
};



uos.checkelementdata  = function() {
	uos.logging = true;
	jQuery.each(uos.elements, function(index,elementdata) {
		var newelementid = '#'+index;
		$newelement = jQuery('body').find(newelementid);	
		if ($newelement.length>0) {
			//uos.log('afterinit',$newelement.attr("id"),$newelement.attr("title"),uos.getelementdata($newelement));
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



/*
Array.prototype.inArray = function(comparer) { 
    for(var i=0; i < this.length; i++) { 
        if(comparer == this[i]) return true; 
    }
    return false; 
};
*/ 

Object.values = function(object) {
  var values = [];
  for(var property in object) {
    values.push(object[property]);
  }
  return values;
}

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


var toType = function(obj) {
  return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
}


//http://stackoverflow.com/questions/12551635/jquery-remove-duplicates-from-an-array-of-strings
function unique(list) {
  var result = new Array();
  //uos.log('xxx',typeof(result));
  $.each(list, function(i, e) {
    if ($.inArray(e, result) == -1) result.push(e);
  });
  return result;
}

/* enhance $.getSctipt to handle mutiple scripts */
var getScript = jQuery.getScript;

jQuery.getScript = function( resources, callback ) {

	var counter = 0;
	var length = resources.length;
	var handler = function() { counter++; };
	var deferreds = [];
  $.ajaxSetup({ cache: true });	
	for (var idx = 0 ; idx < length; idx++ ) {
		deferreds.push(
			getScript( resources[ idx ], handler )
		);
	}
  $.ajaxSetup({ cache: false });	
	jQuery.when.apply( null, deferreds ).then(function() {
		callback&&callback();
	});
};


// remove when we've updated all the variables
//var universeos = uos;



// source : http://html5demos.com/dnd-upload#view-source
/*
var holder = document.getElementById('holder'),
    tests = {
      filereader: typeof FileReader != 'undefined',
      dnd: 'draggable' in document.createElement('span'),
      formdata: !!window.FormData,
      progress: "upload" in new XMLHttpRequest
    }, 
    support = {
      filereader: document.getElementById('filereader'),
      formdata: document.getElementById('formdata'),
      progress: document.getElementById('progress')
    },
    acceptedTypes = {
      'image/png': true,
      'image/jpeg': true,
      'image/gif': true
    },
    progress = document.getElementById('uploadprogress'),
    fileupload = document.getElementById('upload');

"filereader formdata progress".split(' ').forEach(function (api) {
  if (tests[api] === false) {
    support[api].className = 'fail';
  } else {
    // FFS. I could have done el.hidden = true, but IE doesn't support
    // hidden, so I tried to create a polyfill that would extend the
    // Element.prototype, but then IE10 doesn't even give me access
    // to the Element object. Brilliant.
    support[api].className = 'hidden';
  }
});

function previewfile(file) {
  if (tests.filereader === true && acceptedTypes[file.type] === true) {
    var reader = new FileReader();
    reader.onload = function (event) {
      var image = new Image();
      image.src = event.target.result;
      image.width = 250; // a fake resize
      holder.appendChild(image);
    };

    reader.readAsDataURL(file);
  }  else {
    holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size/1024|0) + 'K' : '');
    console.log(file);
  }
}

function readfiles(files) {
    debugger;
    var formData = tests.formdata ? new FormData() : null;
    for (var i = 0; i < files.length; i++) {
      if (tests.formdata) formData.append('file', files[i]);
      previewfile(files[i]);
    }

    // now post a new XHR request
    if (tests.formdata) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/devnull.php');
      xhr.onload = function() {
        progress.value = progress.innerHTML = 100;
      };

      if (tests.progress) {
        xhr.upload.onprogress = function (event) {
          if (event.lengthComputable) {
            var complete = (event.loaded / event.total * 100 | 0);
            progress.value = progress.innerHTML = complete;
          }
        }
      }

      xhr.send(formData);
    }
}

if (tests.dnd) { 
  holder.ondragover = function () { this.className = 'hover'; return false; };
  holder.ondragend = function () { this.className = ''; return false; };
  holder.ondrop = function (e) {
    this.className = '';
    e.preventDefault();
    readfiles(e.dataTransfer.files);
  }
} else {
  fileupload.className = 'hidden';
  fileupload.querySelector('input').onchange = function () {
    readfiles(this.files);
  };
}
*/	


uos.post = function($element,action,parameters,files) {

	var elementdata = uos.getelementdata($element);

  var postData = new FormData();
  
  var jsonparameters = JSON.stringify(parameters);
  
  postData.append("target", elementdata.guid);
  
  postData.append("action", action);

  postData.append("display", 'uosio');
  
  postData.append("jsonparameters", jsonparameters);
  
  uos.log('uos.post',files);
  
  if (files instanceof FileList) {
  	uos.log('files found');
		for (var i = 0; i < files.length; i++) {
		  uos.log("Dropped File : ", files[i]);
		  //filenames.push(files[i].name + ' (' + uos.getReadableFileSizeString(files[i].size) + ')');
		  //$requestinfo.append('<div class="uos-file"><i class="fa fa-file-text"></i><div class="uos-file-title">'+files[i].name+'</div>');
		  postData.append("files[]", files[i]);
		}
	} else {
		uos.log('files not found');
	}
	
	$actionstatus = uos.addactionstatus($element,'Posting Action');

	//if (parameters.files) {
  //	postData.append("files", parameters.files);  
  //	delete parameters.files;
  //}
  
	uos.log('uos.post:request',elementdata.guid,action,parameters);
  
	var uosrequest = new XMLHttpRequest();
	uosrequest.open('POST', '/global/uos.php?random=' + (new Date).getTime());// + '&debugrequest');
	//xhr.setRequestHeader("X_FILENAME", file.name);
	
	//console.log('uos.dropfiles',elementdata,postData);
	
	uosrequest.upload.addEventListener('progress', function(event) {
		var progresspercent = (event.loaded / event.total) * 100;
		//$progressslider.width(progresspercent+ '%');
		uos.updateactionprogress($element,progresspercent);
		console.log('Uploading: ' + (Math.round(progresspercent)+ '%'),event);
	});
	
	uosrequest.onload = function(event) {
		//$.growl.notice({ title : 'Uploaded File(s)', message:  filenames.join(', ') + ' into ' + $element.attr('title'), location : 'br'  });	
		uos.removeactionstatus($element);
		//$requestinfo.remove();
		var data = JSON.parse(event.target.response);
		
		uos.log('uos.post:response',event.target,data);
		
		//var $loadedcontent = jQuery(data.content);
  	//var datacontentclean = data.content.replace("(?s)<!--\\[if(.*?)\\[endif\\] *-->", "");
  	
  	//uos.log('uos.post:datacontentclean',datacontentclean);	
  	//var $datadom = jQuery('<body>'+data.content+'</body>').first().next();
  	//var $datadom = jQuery('<body>'+data.content+'</body>').first();
  	var $dialog = jQuery('#dialog');
  	$dialog.empty().append(data.content);
  	uos.initalizeallelements($dialog, data.elementdata);  	

		$dialog.children().each(function (index) {
			BootstrapDialog.alert($(this));//$loadedcontent);
		});
		uos.log($dialog,data.elementdata);

		/*
		jQuery.each(data.elementdata, function(index,elementdata) {
			var newelementid = '#'+index;
			$newelement = $loadedcontent.find(newelementid).addBack(newelementid);	
			if ($newelement.length>0) {
				//uos.log('uos.loadcontent.preinit',$newelement,newelementid,elementdata);
				uos.initializeelement($newelement,elementdata);
			}
		});
		//uos.log(uos.getelementdata($loadedcontent))
		$element.removeClass('uos-processing');
		$element.replaceWith($loadedcontent); 
		*/	
	};

	
	uosrequest.send(postData);
}


uos.addactionstatus = function($element,$requestinfo) {
	var $requestelement = jQuery('<div class="uos-request"/>');
	$requestelement.append('<h1><i class="fa fa-cog fa-spin"></i> Adding files</h1>');
	var $requestinfo = jQuery('<div class="uos-request-info"/>');
	$requestelement.append($requestinfo);
	$progressindicator = jQuery('<div class="uos-progress-indicator"></div>');
	$progressslider = jQuery('<div class="uos-progress-slider"></div>');
	$progressindicator.append($progressslider);
	$requestelement.append($progressindicator);
	$element.append($requestelement);
}

uos.updateactionprogress = function($element,progresspercent) {
	$progressslider = $element.find('div.uos-progress-slider');
	$progressslider.width(progresspercent+ '%');
}

uos.updateactioninfo = function($element,$requestinfo) {
	$requestinfo = $element.find('div.uos-request-info');
	$requestelement.append($requestinfo);
}

uos.removeactionstatus = function($element) {
	$element.find('div.uos-request').remove();
}


uos.dropfiles = function($element,files) {
    //debugger;
		var elementdata = uos.getelementdata($element);
    uos.log('file drop');
    //uos.log(e.dataTransfer.files);
    var filenames = [];
    $element.addClass('uos-processing');
    var $requestelement = jQuery('<div class="uos-request"/>');
    $requestelement.append('<h1><i class="fa fa-cog fa-spin"></i> Adding files</h1>');
    
    var postData = new FormData();
    
    postData.append("target", elementdata.guid);
    
    postData.append("action", 'dropfiles');

    postData.append("display", 'uosio');
		
		//postData.append("file", files[0]);

		console.log('uos.dropfiles',elementdata, postData);
    
		//postData.append('files', files);
    //postData.append("debugrequest", '1');
    //postData.append("debugresponse", '1');
    //postData.append("debugrender", '1');
    
    var $requestinfo = jQuery('<div class="uos-request-info"/>');

		for (var i = 0; i < files.length; i++) {
		  uos.log("Dropped File : ", files[i]);
		  filenames.push(files[i].name + ' (' + uos.getReadableFileSizeString(files[i].size) + ')');
		  $requestinfo.append('<div class="uos-file"><i class="fa fa-file-text"></i><div class="uos-file-title">'+files[i].name+'</div>');
		  postData.append("files[]", files[i]);
		}

    $requestelement.append($requestinfo);
    
    $progressindicator = jQuery('<div class="uos-progress-indicator"></div>');
    
    $progressslider = jQuery('<div class="uos-progress-slider"></div>');
    
    $progressindicator.append($progressslider);

		$requestelement.append($progressindicator);
    
    $element.append($requestelement);

		uos.notification({ 
			title : 'Dropped File(s)', 
			message:  filenames.join(', ') + ' into ' + $element.attr('title'), 
		});
    /*    				  
		$.growl.notice({ 
			title : 'Dropped File(s)', 
			message:  filenames.join(', ') + ' into ' + $element.attr('title'), 
			location : 'br'  
		});
		*/
		// sort out preview
		$element.addClass('uos-processing');
		
		//var formData = tests.formdata ? new FormData() : null;
		//for (var i = 0; i < files.length; i++) {
		//  if (tests.formdata) formData.append('file', files[i]);
		
		//}
		
		// now post a new XHR request
		//if (tests.formdata) {

		var uosrequest = new XMLHttpRequest();
		uosrequest.open('POST', '/global/uos.php?random=' + (new Date).getTime());// + '&debugresponse');
		//uosrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		//xhr.setRequestHeader("X_FILENAME", file.name);
		
		console.log('uos.dropfiles',elementdata,postData);
		
		uosrequest.upload.addEventListener('progress', function(event) {
			var progresspercent = (event.loaded / event.total) * 100;
			$progressslider.width(progresspercent+ '%');
			//console.log('Uploading: ' + (Math.round(progresspercent)+ '%'),event);
		});
		
		uosrequest.onload = function(event) {
		
			$element.removeClass('uos-processing');
  		//$element.replaceWith($loadedcontent); 
  		$element.find('.uos-request').remove();
		
  		uos.log('uos.dropfiles:response',event.target);
		  //progress.value = progress.innerHTML = 100;
			$.growl.notice({ title : 'Uploaded File(s)', message:  filenames.join(', ') + ' into ' + $element.attr('title'), location : 'br'  });	
			
			//$requestinfo.remove();
			var data = JSON.parse(event.target.response);
			
  		uos.log('uos.dropfiles:response',event.target,data);
  		
  		//remove comments as fix?
  		//var datacontentclean = data.content.replace("(?s)<!--\\[if(.*?)\\[endif\\] *-->", "");
  		//uos.log('uos.dropfiles:datacontentclean',datacontentclean);
  		//BootstrapDialog.alert(datacontentclean);
  		
  		
	  	var $dialog = jQuery('#dialog');
	  	$dialog.empty().append(data.content);
	  	uos.initalizeallelements($dialog, data.elementdata);  	
	
			$dialog.children().each(function (index) {
				BootstrapDialog.alert($(this));//$loadedcontent);
			});
			uos.log($dialog,data.elementdata);
  		/*
			var $loadedcontent = $(data.content);
			
			jQuery.each(data.elementdata, function(index,elementdata) {
				var newelementid = '#'+index;
				$newelement = $loadedcontent.find(newelementid).addBack(newelementid);	
				if ($newelement.length>0) {
					//uos.log('uos.loadcontent.preinit',$newelement,newelementid,elementdata);
					uos.initializeelement($newelement,elementdata);
				}
			});
			*/
			//uos.log(uos.getelementdata($loadedcontent))

		};
		uosrequest.setRequestHeader("Content-Type","multipart/form-data");
		uosrequest.send(postData);	
};


uos.requirestylesheet = function(cssurl) {
	if($('link[rel*=style][href="'+cssurl+'"]').length==0) {
	    $("head").append('<link rel="stylesheet" type="text/css" href="'+cssurl+'">');
	}
};


uos.microtime = function(get_as_float) {
  //  discuss at: http://phpjs.org/functions/microtime/
  // original by: Paulo Freitas
  //   example 1: timeStamp = microtime(true);
  //   example 1: timeStamp > 1000000000 && timeStamp < 2000000000
  //   returns 1: true

  var now = new Date()
    .getTime() / 1000;
  var s = parseInt(now, 10);

  return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
};

uos.setcurrentlocation = function(coords) {

};


// Check for geolocation support
uos.getcurrentlocation = function() {
	if (navigator.geolocation) {
	// Use method getCurrentPosition to get coordinates	
		navigator.geolocation.getCurrentPosition(function (position) {
			// Access them accordingly
			uos.session.coordinates = (position.coords);
			uos.log('uos.getcurrentlocation',uos.session.coordinates);
		});
	}
};





jQuery(document).ready(function() {
//jQuery(window).load(function() {
	uos.logging = true;
  uos.initialize();
});