<?php if ($entity->usereditable) : ?>
<div class="value"><?php print (string) $entity->value;?></div>
<textarea class="editvalue" name="<?php print (string) $entity->key;?>"><?php print (string) $entity->value;?></textarea>
<?php else : ?>
<hidden class="editvalue" name="<?php print (string) $entity->key;?>" value="<?php print (string) $entity->value;?>"/>
<?php endif; ?>