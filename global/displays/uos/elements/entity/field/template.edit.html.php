<?php if ($entity->usereditable) : ?>
<textarea class="editvalue" name="<?php print (string) $entity->key;?>"><?php print (string) $entity->value;?></textarea>
<?php else : ?>
<hidden class="editvalue" name="<?php print (string) $entity->key;?>" value="<?php print (string) $entity->value;?>"/>
<?php endif; ?>