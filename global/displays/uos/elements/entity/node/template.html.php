<?php print rendernew($entity->properties,$render->displaystring); ?>
<div class="uos-element-children <?php print ($render->childcount>0)?'not-empty':'empty';?>" data-children="<?php print count($entity->children);?>">
<h2>Children</h2>
<?php //print rendernew($entity->children,$render->displaystring); ?>
<?php print rendernew($entity->children,'html'); ?>
</div>
<?php //print render($entity->getactions()); ?>
<div class="clearboth"></div>	
<?php //throw new Exception('Division by zero.');?>
