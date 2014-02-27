// Universe / Node functions

$(document).ready(function() {

	//$(document).bind('keydown', 'Ctrl+s', saveChanges);
	
	$(document).bind('keyup keydown', handleKeyboardModifiers);
	
	$(document).bind('keydown', 'backspace', handleBackspace);
	
	$(document).bind('keydown', 'meta+s', saveChanges);
	
	$(document).bind('keydown', 'ctrl+s', saveChanges);

	$(document).bind('keydown', 'meta+c', handleCopy);
	
	$(document).bind('keydown', 'ctrl+c', handleCopy);

  $(window).focus(function() {
      universeos.log('Focus');
  });

  $(window).blur(function() {
      universeos.log('Blur');
  });
  
 	$(window).resize(function() {
      universeos.log('Resize');
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