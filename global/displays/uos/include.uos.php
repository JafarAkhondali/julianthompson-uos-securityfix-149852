<?php


// For PHP without Anonymous functions

function DISPLAY_attributestostring($attributes) {
	$keys = array_keys($attributes);
	return join(' ', array_map(DISPLAY_attributestostring_callback,$keys,$attributes));
}

function DISPLAY_attributestostring_callback($key,$value) {
   if (is_bool($value)) {
      return $value?$value:'';
   }
   return $key.'="'.$value.'"';	
}

/*
function DISPLAY_attributestostring($attributes) {
	return join(' ', array_map(function($sKey) use ($attributes) {
	
	   if (is_bool($attributes[$sKey])) {
	      return $attributes[$sKey]?$sKey:'';
	   }

	   return $sKey.'="'.$attributes[$sKey].'"';

	}, array_keys($attributes)));
}
*/