<?php 
$filedata = file_get_contents($entity->filepath->fullpath());
?>
<?php print render($entity->properties); ?>
<filedata><?php print 'data:image/' . $type . ';base64,' . base64_encode($filedata);?></filedata>
<children><?php print render($entity->children); ?></children>