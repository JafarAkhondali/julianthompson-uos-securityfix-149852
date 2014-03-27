<?php $value = $entity->value;?>
<label class="key"><?php print $entity->key;?></label> 
<span class="separator">:</span> 
<span class="value">
	<input class="field-boolean-value" type="checkbox" checked>
	<!--
	<div class="btn-group" data-toggle="buttons">
	  <label class="btn btn-primary <?php if ($value==FALSE) echo 'active';?>">
	    <input type="radio" name="options" id="option1" <?php if ($value==FALSE) echo 'checked=""';?>> Off
	  </label>
	  <label class="btn btn-primary <?php if ($value==TRUE) echo 'active';?>">
	    <input type="radio" name="options" id="option2" <?php if ($value==TRUE	) echo 'checked=""';?>> On
	  </label>
	</div>
	-->
</span>
<script>$('.btn').button();</script>