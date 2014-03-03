
var universeos = {

	//dragSrcEl = null;

  log: function() {
  		if (console && console.log) {
  			console.log(arguments);
  		}
      //console.log.apply(this,arguments);
  },

	selectNode: function($element, multiple) {
		universeos.log($element.attr('title') + ' selected');
		$element.addClass('selected');
		universeos.updateSelectedCount();
	},
	
	deselectNode: function($element) {
		$element.removeClass('selected');
		universeos.updateSelectedCount();
	},
	
	isSelected: function($element) {
		return $element.hasClass('selected');
	},

	getAllNodes: function() {
		return jQuery('div.node');
	},
	
	getSelectedNodes: function() {
		return jQuery('div.node.selected');
	},
	
	clearSelected: function() {
		jQuery('div.node.selected').removeClass('selected');
		universeos.updateSelectedCount();	
	},

	updateSelectedCount: function() {
		universeos.buildToolbar();
		var count = universeos.getSelectedNodes().length;
		universeos.log('updateSelectedCount', count);
		jQuery('#universe-selected-count').text(count);	
	},
	
	getSelectedTitles: function() {
		var titles = [];
		jQuery('div.node.selected').each(function() {
			titles.push($(this).attr('title'));
		});
		return titles;
	},
	
	buildToolbar: function() {
		universeos.log('Build Toolbar');
		var toolbar = '';
		var actions = universeos.getActions();	
		//console.log(actions,'xxxxxxx');
		
		// clear existing actions from toolbar
		jQuery('#universe-actions').empty();
		
		// add relevant actions
		jQuery.each(actions, function(index,action) {
			//universeos.log('xxx',action.title);
			var $control = jQuery('<i></i>');
			$control.attr('title',action.title);
			$control.addClass('fa');
			$control.click(function () {
				universeos.callAction(action);
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
	},
	
	getActions: function (e) {
		var actions = new Array();
		// if no selection
		if (jQuery('div.node.selected').length>0) {
			jQuery('div.node.selected').each(function(e) {
				var actionstring = jQuery(this).data('actions');
				actionnames = actionstring.split(',');
				jQuery.each(actionnames, function(index,actionname) {
					if (universeos.node.hasAction(actionname)) {
						if (!actions.inArray(universeos.node.actions[actionname])) {
							//universeos.log(universeos.node.actions[actionname]);
							actions.push(universeos.node.actions[actionname]);
						}
					}
				});
			});
		} else {
			actions = universeos.global.actions;
		}
		//console.log(actions);
		return actions;
	},
	
	callAction: function (action) {
		universeos.log('Calling action : ' + action.title + ' on :');
		jQuery('div.node.selected').each(function(e) {
			universeos.log($(this).attr('title') + ' ');			
		});
		if (action.action) {
			action.action(jQuery('div.node.selected'));
		} else {
			universeos.log('Action not found.');
		}
	},

	getReadableFileSizeString: function(fileSizeInBytes) {	
		var i = -1;
		var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
		do {
		    fileSizeInBytes = fileSizeInBytes / 1024;
		    i++;
		} while (fileSizeInBytes > 1024);
		
		return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
	},
	
	overlay: function(toggle) {
		if (toggle) {
			var $overlay = jQuery('<div class="uos-overlay"><i class="fa fa-refresh fa-spin"></i><div class="message">Updating</div></div>');
			jQuery('body').append($overlay);
		} else {
			jQuery('div.uos-overlay').remove();
		}
	},
	
	load : function() {
		universeos.buildToolbar();
		filtermessages('jmt');
	}
}

//var uos = universeos;

universeos.node = {

	hasAction: function (actionname) {
		return (universeos.node.actions[actionname])?true:false;
	},
	
	actions: {
		init : {
			title : 'Initialise',	
			icon : 'fa-wrench'		
		},
		reload : {
			title : 'Reload',
			icon : 'fa-refresh'						
		},
		add : {
			title : 'Add',		
			icon : 'fa-plus-circle'	
		},
		displayup : {
			title : 'Change Display',	
			icon : 'fa-caret-left',		
			action : function() {
				jQuery.ajax({
				  type: "POST",
				  url: '/global/uos.php',
				  context: document.body,
				  data: {
				  	selection : [4567898765,5645342341],
				  	universe : 'julian'
				  },
				  success: function(e) {
				  	universeos.log(e);
				  },
				  //dataType: dataType
				});
			}
		},
		displaydown : {
			title : 'Change Display',	
			icon : 'fa-caret-right',	
			action : function() {

			  // modified from view-source:http://mrdoob.github.io/three.js/examples/css3d_periodictable.html
				var camera, scene, renderer;
				var controls;
	
				var objects = [];
				var targets = { table: [], sphere: [], helix: [], grid: [] };


				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 3000;
				scene = new THREE.Scene();
				
				init();
				animate();
				
				function init() {
					var $nodes = universeos.getAllNodes();
					$nodes.each(function (index) {
						jQuery(this).addClass('element');
						jQuery(this).addClass('three-d');
						console.log(this);
						var object = new THREE.CSS3DObject( this ); //??
						object.position.x = Math.random() * 4000 - 2000;
						object.position.y = Math.random() * 4000 - 2000;
						object.position.z = Math.random() * 4000 - 2000;
						scene.add(object);
						objects.push( object );
					});				
	
					
					//console.log(objects);
			
			
			
					var l = objects.length;
					
					// table 
					
					jQuery.each(objects, function(i,o) {
						var object = new THREE.Object3D();
						
						// we can use some element property here
						//object.position.x = ( table[ i + 3 ] * 140 ) - 1330;
						//object.position.y = - ( table[ i + 4 ] * 180 ) + 990;

						//targets.table.push( object );
					});
					
					// sphere
					
					var vector = new THREE.Vector3();
					
					jQuery.each(objects, function(i,o) {
		
						var phi = Math.acos( -1 + ( 2 * i ) / l );
						var theta = Math.sqrt( l * Math.PI ) * phi;
		
						var object = new THREE.Object3D();
		
						object.position.x = 400 * Math.cos( theta ) * Math.sin( phi );
						object.position.y = 400 * Math.sin( theta ) * Math.sin( phi );
						object.position.z = 400 * Math.cos( phi );
		
						vector.copy( object.position ).multiplyScalar( 2 );
		
						object.lookAt( vector );
		
						targets.sphere.push( object );
		
					});
					
					// helix
					var vector = new THREE.Vector3();					

					jQuery.each(objects, function(i,o) {
						var phi = i * 0.175 + Math.PI;
	
						var object = new THREE.Object3D();
	
						object.position.x = 900 * Math.sin( phi );
						object.position.y = - ( i * 8 ) + 450;
						object.position.z = 900 * Math.cos( phi );
	
						vector.x = object.position.x * 2;
						vector.y = object.position.y;
						vector.z = object.position.z * 2;
	
						object.lookAt( vector );
	
						targets.helix.push( object );					
					});
					
					// grid			

					jQuery.each(objects, function(i,o) {
	
						var object = new THREE.Object3D();
	
						object.position.x = ( ( i % 5 ) * 400 ) - 800;
						object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
						object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;
	
						targets.grid.push( object );
	
					});
	
					renderer = new THREE.CSS3DRenderer();
					renderer.setSize( window.innerWidth, window.innerHeight );
					renderer.domElement.style.position = 'absolute';
					jQuery('#container').html( renderer.domElement );
					
					controls = new THREE.TrackballControls( camera, renderer.domElement );
					controls.rotateSpeed = 0.5;
					controls.minDistance = 500;
					controls.maxDistance = 6000;
					controls.addEventListener( 'change', render );
					
					//transform( targets.helix, 5000 );
					transform( targets.sphere, 1000 );
				}	


				function transform( targets, duration ) {
	
					TWEEN.removeAll();
					console.log(objects.length);
					for ( var i = 0; i < objects.length; i ++ ) {
	
						var object = objects[ i ];
						var target = targets[ i ];
	
						new TWEEN.Tween( object.position )
							.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
							.easing( TWEEN.Easing.Exponential.InOut )
							.start();
	
						new TWEEN.Tween( object.rotation )
							.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
							.easing( TWEEN.Easing.Exponential.InOut )
							.start();
	
					}
	
					new TWEEN.Tween( this )
						.to( {}, duration * 2 )
						.onUpdate( render )
						.start();
	
				}
	
				function onWindowResize() {
	
					camera.aspect = window.innerWidth / window.innerHeight;
					camera.updateProjectionMatrix();
	
					renderer.setSize( window.innerWidth, window.innerHeight );
	
					render();
	
				}
	
				function animate() {
	
					requestAnimationFrame( animate );
	
					TWEEN.update();
	
					controls.update();
	
				}
	
				function render() {
	
					renderer.render( scene, camera );
	
				}		
			}	
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
		cancel : {
			title : 'Cancel',			
			icon : 'fa-times',
			action : function($selection) {
				universeos.clearSelected();
			}			
		}
	}
};

universeos.node_device = {

};

universeos.global = {

	hasAction: function (actionname) {
		return (universeos.global.actions[actionname])?true:false;
	},
	
	actions: {
		add : {
			title : 'Add',		
			icon : 'fa-plus-circle'	
		},
		trace : {
			title : 'Trace',
			icon : 'fa-sign-in',
			action : function() {
				jQuery('#input').toggleClass('opened');
			}
		},
	}
};


var dragSrcEl = null;

function handleDragStart(e) {

  //e.stopPropagation(); 
  // Target (this) element is the source node.
  //universeos.log(typeof $(this).attr('data-downloadurl'));
	//jQuery(this).addClass('selected');
	if (!universeos.isSelected(jQuery(this))) {
		handleNodeClick(this);
	}
	
  universeos.log($(this).data('downloadurl'));
  universeos.log($(this).data('guid'));
  universeos.log($(this).attr('title'));
  var title = $(this).attr('title');
  var guid = $(this).data('guid');
 	if (isShiftHeld() && $(this).attr('data-downloadurl')) {
 	  universeos.log('ccc');
		downloadurl = $(this).data('downloadurl');
	} else {
	//application/octet-stream
		//downloadurl = "application/x-url:"+title+".url:http://universeos.localhost/global/admin/dragdrop/data/"+guid+"/"+guid+".url";
		downloadurl = "application/octet-stream:"+title+".webloc:http://universeos.localhost/global/admin/dragdrop/data/"+guid+"/"+guid+".webloc"
	}
	e.dataTransfer.setData("DownloadURL",downloadurl);

  //this.style.opacity = '0.4';

  dragSrcEl = this;

  universeos.log('dragstart',$(this).attr('title'));

  e.dataTransfer.effectAllowed = 'move';
  
  //$(e.currentTarget).addClass('dragging');
	//var clone = event.target.cloneNode(true);
  //event.target.parentNode.appendChild(clone);
  //event.target.ghostDragger = '<div>xxxx</div>';//SET A REFERENCE TO THE HELPER
  
  //$(clone).addClass('dragging');
  //$(this).addClass('dragging');
  //e.dataTransfer.setData('text/html', this.innerHTML);
  //var dragIcon = jQuery('<div>xxx</div>');
  universeos.selectNode(jQuery(this),true);
  var iconcount = document.getElementById("universe-status-icon");
	var crt = iconcount.cloneNode(true);
	crt.id = "universe-drag-helper";
	$(crt).addClass('drag-helper');
  //crt.style.backgroundColor = "red";
  //crt.style.position = "absolute"; crt.style.top = "0px"; crt.style.left = "-100px";
  document.body.appendChild(crt);
  //var iconcount = jQuery('<div class="xxx">Test</div>');
  universeos.log('iconcount');
 	e.dataTransfer.setDragImage(crt,-1,-1);
 	//e.dataTransfer.setDragImage(crt,-1,-1);
}

function handleDragOver(e) {
	//universeos.log(dragContainsFiles(e)?"File":"Node");
	//universeos.log(dragGetPayloadTypes(e));
  //if (e.stopPropagation) {
  //e.stopPropagation(); // Stops some browsers from redirecting.
  //}
	if (!universeos.isSelected(jQuery(this))) {
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
  //universeos.log('dragenter',$(this).attr('title'));
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
  //universeos.log('leave',$(this).attr('title'));
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

  //universeos.log('Dropped',e);
  universeos.log('drop',$(this).attr('title'),$(e.target).attr('title'));
  $(this).removeClass('dragging');
  $(this).removeClass('dragging-hover-target');
  $(e.target).removeClass('dragging-target');

  if (e.stopPropagation) {
    e.stopPropagation(); // Stops some browsers from redirecting.
  }
  e.preventDefault();
  
  var data = e.dataTransfer.getData('text');
  universeos.log(data);

  // Don't do anything if dropping the same column we're dragging.
  if (dragSrcEl != this) {
    // Set the source column's HTML to the HTML of the column we dropped on.
    //dragSrcEl.innerHTML = this.innerHTML;
    //this.innerHTML = e.dataTransfer.getData('text/html');
    // transfer data file?
    if (e.dataTransfer.files.length>0) {
    	universeos.log('file drop');
    	//universeos.log(e.dataTransfer.files);
    	var filenames = [];
    	for (var i = 0; i < e.dataTransfer.files.length; i++) {
      	universeos.log("Dropped File : ", e.dataTransfer.files[i]);
      	filenames.push(e.dataTransfer.files[i].name + ' (' + universeos.getReadableFileSizeString(e.dataTransfer.files[i].size) + ')');
      }
      
      $.growl.notice({ title : 'Dropped File(s)', message:  filenames.join(', ') + ' into ' + $(this).attr('title'), location : 'br'  });

    } else {
      //universeos.log('node drop');
    	//universeos.log(e.dataTransfer);
			var titles = universeos.getSelectedTitles();
    	$.growl.notice({ title : 'Dropped Node(s)', message: 'Dropped : ' + titles.join(', ') + ' onto ' + $(this).attr('title'), location : 'br' });
    	universeos.log('Dropped Node : ' + $(dragSrcEl).attr('title') + ' onto ' + $(this).attr('title'));
    	//universeos.log(e.dataTransfer.getData('text/html'));
    }
  	$(this).removeClass('dragging-target');    
  }

  $(this).removeClass('dragging-noaccept');  
  //jQuery('#universe-drag-helper').remove();

  return false;
}

function handleDragEnd(e) {
  // this/e.target is the source node.
	universeos.log('DragEnd');
  //$('.node').removeClass('over');
  jQuery('#universe-drag-helper').remove();
}

function handleKeyboardModifiers(e) {
	//universeos.log(e);
	$('body').toggleClass('shift-pressed', e.shiftKey);
	$('body').toggleClass('meta-pressed', e.metaKey);
	$('body').toggleClass('ctrl-pressed', e.ctrlKey);
	$('body').toggleClass('alt-pressed', e.altKey);
}

function handleBackspace(e) {
	universeos.log('Delete nodes');
  //if (e.stopPropagation) {
  //  e.stopPropagation(); // Stops some browsers from redirecting.
  //}
  e.preventDefault();
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
	//universeos.log(e);
	if (isShiftHeld()) {
		universeos.log('Shift select');
	} else {
		if (universeos.isSelected(jQuery(this))) {
		//if (!e.ctrlKey) {
			if (!isMetaHeld()) {
				universeos.clearSelected();
			}
			universeos.deselectNode(jQuery(this),isMetaHeld());
		} else {
			if (!isMetaHeld()) {
				universeos.clearSelected();
			}
			universeos.selectNode(jQuery(this),isMetaHeld());
		}
	//jQuery(this).toggleClass('selected');
	}
	e.preventDefault();
	e.stopPropagation();
	//return false;
}

function handleSave(e) {
  event.preventDefault();
  universeos.log('saving?');
}

function handleCopy(e) {
  event.preventDefault();
  universeos.log('copying?');
}

function saveChanges(e){ 
		e.preventDefault();
		e.stopPropagation();
		universeos.log('Saved');
}


// Check for geolocation support

if (navigator.geolocation) {

// Use method getCurrentPosition to get coordinates

	navigator.geolocation.getCurrentPosition(function (position) {

		// Access them accordingly

		universeos.log(position.coords.latitude + ", " + position.coords.longitude);
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
  jQuery('#inputmessages > ul > li').each(function() {
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



