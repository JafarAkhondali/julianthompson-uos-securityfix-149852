<?php $indexes = 1;?>
<?php foreach ($entity as $index => $subentity) : ?>
	<?php print render($subentity);?>
	<?php $indexes++;?>
<?php endforeach; ?>