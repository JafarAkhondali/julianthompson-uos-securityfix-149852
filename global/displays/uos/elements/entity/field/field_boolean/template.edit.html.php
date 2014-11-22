<div class="toggle-switch">
<input type="hidden" name="<?php print (string) $entity->key;?>" value="0" />
<input class="field-boolean-value" type="checkbox" name="<?php print (string) $entity->key;?>-dummy" value="1" <?php if ($entity->value) echo "checked";?>/>
</div>