uos.displays['field.edit.html'] = uos.extenddisplay('field');

uos.displays['field.edit.html'].actions.init.handler = uostype_field_edit_initialize;

console.log('Included display \'field.edit.html\'',uos.displays['field.edit.html']);

function uostype_field_edit_initialize($element,data) {
	//$element.css('border','1px solid green');
	uos.log('uostype_field_edit_initialize',$element.attr('id'),data);
	

	$element.find('.value').bind('click', function(event) {
		console.log('content clicked');
		$(this).addClass('editable');
		$(this).attr('contenteditable', 'true');  
		//$('.save').show();
		uos.isTextSelected(this);
		uostype_field_edit_toolbar_edit();
	});

	$element.find('.value').bind('selectstart', function(event) {
		console.log('selectstart');
		event.stopPropagation();
		$(this).bind('mouseup', function(event){
			//uos.isTextSelected();
			console.log('selectend');
			console.log(uos.getSelectedText());
	    	event.stopPropagation();
		});
	});

	$element.find('.value').bind('blur', function(event) {
		console.log('blur');
		$(this).focus();
		event.stopPropagation();
	});
	
	$element.find('.value').bind('drag', function(event) {
		console.log('drag');
		event.stopPropagation();
	});



	$element.find('.editvalue').bind('keypress', function(event) {
		$element.addClass('uos-modified');
		console.log('onchange');
		uos.isTextSelected(this);
	});
	
}	

function uostype_field_edit_toolbar_edit() {
	actions = {};
	actions.selectionbold = {
		title : 'Bold',	
		icon : 'fa-bold',
		handler : function($elements,event) {
			//document.execCommand('formatBlock', false, 'p');
			document.execCommand('bold', false, null);
			event.stopPropagation();
			event.preventDefault();
		}
	};
	actions.selectionitalic = {
		title : 'Italic',	
		icon : 'fa-italic',
		handler : function() {
			document.execCommand('italic', false, null);
		}
	};
	actions.selectionalignleft = {
		title : 'Left Align',	
		icon : 'fa-align-left',
		handler : function() {
		}
	};
	actions.selectionalignright = {
		title : 'Center',	
		icon : 'fa-align-center',
		handler : function() {
		}
	};
	actions.selectionalignright = {
		title : 'Right Align',	
		icon : 'fa-align-left',
		handler : function() {
		}
	};
	actions.selectionalignjustify = {
		title : 'Center',	
		icon : 'fa-align-justify',
		handler : function() {
		}
	};
	actions.undo = {
		title : 'Undo',	
		icon : 'fa-undo',
		handler : function() {
		}
	};
	actions.copy = {
		title : 'Copy',	
		icon : 'fa-copy',
		handler : function() {
		}
	};
	actions.paste = {
		title : 'Paste',	
		icon : 'fa-paste',
		handler : function() {
		}
	};
	actions.done = {
		title : 'Done',	
		icon : 'fa-check',
		handler : function() {
			uos.buildToolbar();
		}
	};
	actions.cancel = {
		title : 'Cancel',	
		icon : 'fa-times',
		handler : function() {
			uos.buildToolbar();
		}
	};
		
	uos.buildToolbar(actions);
	
}


