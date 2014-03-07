
<?php

function DISPLAY_attributestostring($attributes) {
	return join(' ', array_map(function($sKey) use ($attributes) {
	   if(is_bool($attributes[$sKey]))
	   {
	      return $attributes[$sKey]?$sKey:'';
	   }
	   return $sKey.'="'.$attributes[$sKey].'"';
	}, array_keys($attributes)));
}