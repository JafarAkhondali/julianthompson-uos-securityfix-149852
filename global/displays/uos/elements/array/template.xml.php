<?php foreach ($entity as $currentkey=>$subentity) : ?>
<?php print rendernew($subentity, array('currentkey'=>$currentkey));?>
<?php endforeach; ?>