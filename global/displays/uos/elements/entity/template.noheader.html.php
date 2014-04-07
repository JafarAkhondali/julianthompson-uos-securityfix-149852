<h1><?php print (string) $entity->title;?></h1>
<h2>(<?php print (string) $entity->guid->value;?>)</h2>
<?php //print rendernew($entity->properties,'html'); ?>
<?php //print rendernew($entity->children,'html'); ?>
<?php //print render($entity->getactions()); ?>
<pre>
<?php print $render->display->preprocess;?>

<?php print $render->display->template;?>

<?php print $render->display->wrapper;?>

<?php print_r($render);?>
</pre>

<div class="clearboth"></div>	