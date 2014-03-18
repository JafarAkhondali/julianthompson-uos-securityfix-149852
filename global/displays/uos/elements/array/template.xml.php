<?php foreach ($entity as $currentkey=>$subentity) : ?>
<?php print render($subentity, array('currentkey'=>$currentkey));?>
<?php endforeach; ?>