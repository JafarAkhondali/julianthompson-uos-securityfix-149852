<?php $type = get_type_data(get_class($entity)); ?>
<div class="<?php print implode(' ',$classes);?>" id="<?php print $instanceid;?>" draggable="true" data-downloadurl="<?php print $entity->downloadurl->value;?>" data-type="<?php print get_class($entity);?>" data-guid="<?php print $entity->guid->value;?>" data-display="default" data-displays="default,teaser,edit" title="<?php print $entity->title->value;?>" data-actions="add,displayup,displaydown,edit,remove,save,cancel" data-accept="*" data-childcount="<?php print count($entity->children);?>">
<?php print $content; ?>
</div>