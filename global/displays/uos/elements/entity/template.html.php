BLAH
<?php print render($entity->properties,$render->displaystring); ?>
<div class="children">
<?php //print render($entity->children,$render->displaystring); ?>
TEASER HTML
<?php print render($entity->children,'teaser.html'); ?>
</div>
<?php //print render($entity->getactions()); ?>
<div class="clearboth"></div>	