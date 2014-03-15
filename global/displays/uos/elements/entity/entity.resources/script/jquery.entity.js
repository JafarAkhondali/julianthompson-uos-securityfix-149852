uos.types['entity'] = {};

uos.types['entity'].extends = null;

uos.types['entity'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_entity_initialize
	},
	
	reload : {
		title : 'Reload',
		icon : 'fa-refresh',
		handler : uostype_entity_reload	
	},
	
	add : {
		title : 'Add',		
		icon : 'fa-plus-circle'	
	},
	
	displayup : {
		title : 'Change Display',	
		icon : 'fa-caret-left',		
		handler: uostype_entity_display_down
	},
	
	displaydown : {
		title : 'Change Display',	
		icon : 'fa-caret-right',	
		handler: uostype_entity_display_up
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
	threed : {
		title : '3D display',
		icon : 'fa-globe',
		handler : uos_three
	}
};


function uostype_entity_initialize($element) {
	//$element.css('border','3px solid red');
  var elementdata = $element.data('uos-data'); 
  
	$element.bind('click', function(event) {
		uostype_entity_click($element,event);
	});

	// bind some events
	$elementheader = $element.find('.uos-header');
	
	$elementheader.bind('click',function(event) {
		uostype_entity_header_click($element,event);
	});
	
	$elementheader.bind('dblclick',function(event) {
		uostype_entity_header_dblclick($element,event);
	});
	
	uos.log('uostype_entity_initialize',$element.attr('id'),elementdata);
}		

function uostype_entity_click($element,event) {
	uos.selectElement($element,isMetaHeld());
	event.preventDefault();
	event.stopPropagation();	
}

function uostype_entity_header_click($element, event) {
  var elementdata = $element.data('uos-data');  
	//alert('clicked header');
	window.location = elementdata.clicktarget;
	event.stopPropagation();
}

function uostype_entity_header_dblclick($element, event) {
	alert('dblclick header');
	
	event.stopPropagation();
}

function uostype_entity_display_up($element, event) {
	var elementdata = $element.data('uos-data');
	var elementid = $element.attr('id');
	//elementdata.displaypaths
	uos.loadcontent($element,"/8834323145.json.html");
}


function uostype_entity_reload($element) {
	uos.loadcontent($element,"/8834323145.json.html");
}



function uostype_entity_display_down($element, event) {
	uos.log('uostype_entity_display_down',$element.data('uos-data'));
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
	  	uos.log(e);
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



