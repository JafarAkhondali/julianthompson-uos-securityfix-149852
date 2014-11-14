uos.displaystest['node'] = uos.displaystest['entity'].extend({
  init: {
		title : 'Initialise',	
		icon : 'fa-wrench',
		handler : uostype_entity_initialize
  },
  reload: {
		title : 'Reload',
		icon : 'fa-refresh',
		handler : uostype_entity_reload	
	},
	add : {
		title : 'Add',		
		icon : 'fa-plus-circle',
		handler : uostype_entity_add	
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
	}
});


uos.displays['node'] = {};

uos.displays['node'].extends = uos.displays['entity'];

uos.displays['node'].actions = {};