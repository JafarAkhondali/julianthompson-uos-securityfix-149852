// Universe / Node functions



jQuery(document).ready(function() {
//jQuery(window).load(function() {

  uos.initialize();
	//console.log(uos.elements);


  /*
	$('.node').each(function() {
	  var $this = $(this);
  	this.addEventListener('dragstart', handleDragStart, false);
  	this.addEventListener('dragenter', handleDragEnter, false);
  	this.addEventListener('dragover', handleDragOver, false);
  	this.addEventListener('dragleave', handleDragLeave, false);
  	this.addEventListener('dragend', handleDragEnd, false);
  	this.addEventListener('drop', handleDrop, false);
  	//this.addEventListener('click', handleNodeClick, false);

  	//$(this).bind("contextmenu",function(e){return false;});
	});
	*/
});


var toType = function(obj) {
  return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
}