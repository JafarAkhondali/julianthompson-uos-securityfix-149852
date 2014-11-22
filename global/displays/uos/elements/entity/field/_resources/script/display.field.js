uos.displays['field'] = uos.extenddisplay(uos.displays['entity']);

uos.displays['field'].title = 'Field';

uos.displays['field'].actions.init.handler = uostype_field_initialize;

uos.displays['field'].actions.getvalue = {
		title : 'Get value',	
		icon : 'fa-wrench',
		handler : function($element) {
			//console.log($element);
			$valuewrapper = $element.find('span.uos-field-value-wrapper');
			$inputelement = $valuewrapper.find(':input');
			if ($inputelement.length>0) {
				return $inputelement.val();					
			} else {
				return $inputelement.html();
			}
			uos.log('uos.displays[\'field\'].actions.getvalue',$valuewrapper.html(),$valuewrapper);
			return $element.find('span.value textarea').val();		
		}
};

console.log('Included display \'field\'',uos.displays['field']);



/* Class Functions */


function uostype_field_initialize($element) {
	//$element.css('border','1px solid green');
	
  var elementdata = uos.getelementdata($element); 
	uos.log('uostype_field_initialize',$element.attr('id'),elementdata);
}		

/*
function uostype_field_getvalue($element) {
	console.log($element);
	return $element.find('span.value').val();
}
*/