uos.types['field_number'] = {};

uos.types['field_number'].extends = null;

uos.types['field_number'].actions = {

	init : {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_field_number_initialize
	},

	deviceon : {
		title : 'On',
		icon : 'fa-power-off',	
	},
	
	deviceoff : {
		title : 'Off',
		icon : 'fa-power-off',				
	},
};


function uostype_field_number_initialize($element) {
	uos.log('uostype_field_number_initialize');
	//$element.find('.btn').button();
	//$element.css('border','1px solid red');
	//$element.find('.btn').css('border','1px solid red');
	$element.bind('click', function(event) {
		event.stopPropagation();
	});
	
	$slider = $element.find('.field-number-value');
	$slider.slider({ 
				max: 100,
				min: 0,
				step: 5
	}).on('slideStop', function(event) {
		uos.log('slidestop');
		/*
		var $this = $(this);
		var sliderdata = $this.data('slider');
		var slidervalue = sliderdata.getValue();
		console.log(slidervalue);
		$brightness.text(event.value + '%');
		$.ajax({
			url: './command.php',
		  data: {
		  	t: target,
		  	c: 'xdim',
		  	l: slidervalue
		  },
		  success: function(data, textStatus, jqXHR) {
				console.log(data);
  			$('#responsedata').html('<pre>'+JSON.stringify(data)+'</pre>');
				process_status(data.status);
		  },
		  dataType: "json"
		});
		*/
	}).on('slide', function(event) {
		/*
			$brightness.text(event.value + '%');
			console.log('move');
		*/
	});

}
