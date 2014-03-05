<!-- start wrapper.field.html.php -->
<div class="field type-<?php print get_class($entity);?> key-<?php print $entity->key;?> <?php print $entity->locked?'locked':'unlocked';?>" uid="<?php print $entity->key;?>" title="<?php print $entity->key;?>">
<?php print $content;?>
</div>
<!-- end wrapper.field.html.php -->