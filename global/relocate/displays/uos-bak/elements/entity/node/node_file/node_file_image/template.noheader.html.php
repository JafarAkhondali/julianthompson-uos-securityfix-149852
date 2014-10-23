<img src="/<?php print (string) $entity->guid->value;?>.file" width="100%"/>
<?php print $render->display->template;?>
<h1><?php print (string) $entity->title;?></h1>
<h2>(<?php print (string) $entity->guid->value;?>)</h2>
<?php //print rendernew($entity->properties,'html'); ?>
<?php //print rendernew($entity->children,'html'); ?>
<?php //print render($entity->getactions()); ?>
<div class="clearboth"></div>	