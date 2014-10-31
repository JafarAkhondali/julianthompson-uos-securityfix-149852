BLAH
<?php print rendernew($entity->properties,$render->displaystring); ?>
<div class="children">
<?php //print rendernew($entity->children,$render->displaystring); ?>
TEASER HTML
<?php print rendernew($entity->children,'teaser.html'); ?>
</div>
<?php //print render($entity->getactions()); ?>
<div class="clearboth"></div>	