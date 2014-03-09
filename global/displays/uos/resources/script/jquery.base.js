// Universe / Node functions



$(document).ready(function() {

	//console.log(uos.elements);
	jQuery.each(uos.elements, function(index,element) {
		var elementId = '#'+index;
		$element = jQuery(elementId);	
		if ($element.length>0) uos.processelement($element);	
	});

	//$(document).bind('keydown', 'Ctrl+s', saveChanges);
	
	$(document).bind('keyup keydown', handleKeyboardModifiers);
	
	$(document).bind('keydown', 'backspace', handleBackspace);
	
	$(document).bind('keydown', 'meta+s', saveChanges);
	
	$(document).bind('keydown', 'ctrl+s', saveChanges);

	$(document).bind('keydown', 'meta+c', handleCopy);
	
	$(document).bind('keydown', 'ctrl+c', handleCopy);

  $(window).focus(function() {
      uos.log('Focus');
  });

  $(window).blur(function() {
      uos.log('Blur');
  });
  
 	$(window).resize(function() {
      uos.log('Resize');
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
	
	uos.load();
});


var toType = function(obj) {
  return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
}