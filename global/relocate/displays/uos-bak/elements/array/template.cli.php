<?php $indexes = 1;?>
<?php foreach ($entity as $index => $subentity) : ?>
<?php print ($index);?> : <?php //print rendernew($subentity);?>
<?php $indexes++;?>
<?php endforeach; ?>