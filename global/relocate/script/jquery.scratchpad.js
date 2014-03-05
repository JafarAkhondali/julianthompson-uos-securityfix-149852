// Universe / Node functions

$(document).ready(function() {

	//$(document).bind('keydown', 'Ctrl+s', saveChanges);
	
	$(document).bind('keyup keydown', handleKeyboardModifiers);
	
	$(document).bind('keydown', 'meta+s', saveChanges);
	
	$(document).bind('keydown', 'ctrl+s', saveChanges);
	
	$(document).bind('keydown', 'ctrl+c', handleCopy);
	
	//$(document).bind('keyup keydown', 'meta', handleMetaPress);
	
	//$(document).bind('keyup keydown', 'ctrl', handleCtrlPress);
	
  //$.hotkeys.add('Ctrl+c', {target:'div#editor', type:'keyup', propagate: true},function(){ alert('copy anyone?');});
  //$.hotkeys.remove('Ctrl+c'); 
  //$.hotkeys.remove('Ctrl+c', {target:'div#editor', type:'keypress'}); 
	
	//$(document).bind('keydown', 'meta', handleSave);

	//$(document).bind('keydown', 'shift', handleShiftDown);
	
	//$(document).bind('keyup', 'ctrl', handleShiftUp);

	//$(document).bind('keydown', 'ctrl+s', handleSave);
	//$(".node").makeDraggable();

  $(window).focus(function() {
      //universeos.log('Focus');
  });

  $(window).blur(function() {
      //universeos.log('Blur');
  });
  
 	$(window).resize(function() {
      //universeos.log('Resize');
  });
  
	$('.node').each(function() {
	  var $this = $(this);
  	this.addEventListener('dragstart', handleDragStart, false);
  	this.addEventListener('dragenter', handleDragEnter, false);
  	this.addEventListener('dragover', handleDragOver, false);
  	this.addEventListener('dragleave', handleDragLeave, false);
  	this.addEventListener('dragend', handleDragEnd, false);
  	this.addEventListener('drop', handleDrop, false);
  	this.addEventListener('click', handleNodeClick, false);
  	//$(this).bind("contextmenu",function(e){return false;});

	});
});

var dragSrcEl = null;

function handleDragStart(e) {
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
	universeos.log(dragContainsFiles(e)?"File":"Node");
	universeos.log(dragGetPayloadTypes(e));
	if (!universeos.isSelected(jQuery(this))) {
  //if ($(this)[0] != $(dragSrcEl)[0]) {
	  if (e.preventDefault) {
	    e.preventDefault(); // Necessary. Allows us to drop.
	  }
	
	  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
	  $(this).addClass('dragging-target');
	  return false;
  }
}

function handleDragEnter(e) {
  // this / e.target is the current hover target.
  //universeos.log('dragenter',$(this).attr('title'));
  if ($(this)[0] != $(dragSrcEl)[0]) {
	  //this.classList.add('over');
	  e.preventDefault();
	  //e.stopPropagation();
	  //if ($(this)[0] != $(e.target)[0]) {
	  	//$(this).addClass('dragging-hover-target');
	  	$(this).addClass('dragging-target');
	  //}
	  return true;
  }
}

function handleDragLeave(e) {
  // this / e.target is previous target element.
  //universeos.log('leave',$(this).attr('title'));
  //this.classList.remove('over');  
  //$(this).removeClass('dragging-hover-target');
  e.stopPropagation();
  $(this).removeClass('dragging-target');
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
    	for (var i = 0; i < e.dataTransfer.files.length; i++) {
      	universeos.log(" File " + i + ":\n(" + (typeof e.dataTransfer.files[i]) + ") : <" + e.dataTransfer.files[i] + " > " + e.dataTransfer.files[i].name + " " + e.dataTransfer.files[i].size + "\n");
      }
    } else {
      //universeos.log('node drop');
    	//universeos.log(e.dataTransfer);
    	universeos.log('Dropped : ' + $(dragSrcEl).attr('title') + ' onto ' + $(this).attr('title'));
    	//universeos.log(e.dataTransfer.getData('text/html'));
    }
  	$(this).removeClass('dragging-target');    
  }
  
  //jQuery('#universe-drag-helper').remove();

  return false;
}

function handleDragEnd(e) {
  // this/e.target is the source node.
	universeos.log('DragEnd');
  //$('.node').removeClass('over');
  jQuery('div.node').removeClass('dragging-target');
  jQuery('#universe-drag-helper').remove();
}

function handleKeyboardModifiers(e) {
	universeos.log(e);
	$('body').toggleClass('shift-pressed', e.shiftKey);
	$('body').toggleClass('meta-pressed', e.metaKey);
	$('body').toggleClass('ctrl-pressed', e.ctrlKey);
	$('body').toggleClass('alt-pressed', e.altKey);
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
		universe.log('Shift select');
	} else {
		if (universeos.isSelected(jQuery(this))) {
		//if (!e.ctrlKey) {
			if (!isMetaHeld()) {
				clearSelected();
			}
			universeos.deselectNode(jQuery(this),isMetaHeld());
		} else {
			if (!isMetaHeld()) {
				universeos.clearSelected();
			}
			universeos.selectNode(jQuery(this),isMetaHeld());
			//event.preventDefault();
			//event.stopPropagation();
		}
	//jQuery(this).toggleClass('selected');
	
	//return false;
}

function handleSave(e) {
  event.preventDefault();
  universeos.log('saving?');
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



/*
$.fn.makeDraggable(){
    $(this).attr('draggable','true');
    $(this).bind('dragstart', function(e){
                e.originalEvent.dataTransfer.effectAllowed = 'move';
                e.originalEvent.dataTransfer.setData('text/html', 'test');
                console.log('dragstart');
                }, 
            false);
}
*/

//$(document).bind('Ctrl+c', function(){ alert('copy anyone?');});


