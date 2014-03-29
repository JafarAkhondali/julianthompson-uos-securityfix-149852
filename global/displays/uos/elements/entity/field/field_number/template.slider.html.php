<?php $value = $entity->value;?>
<label class="key"><?php print $entity->key;?></label> 
<span class="separator">:</span> 
<span class="value">
	<input class="field-number-value" id="" type="text" data-slider-min="0" data-slider-max="100" data-slider-enabled="true" data-slider-step="5" data-slider-value="<?php print ($entity->value);?>" value="<?php print ($entity->value);?>"/>
</span>