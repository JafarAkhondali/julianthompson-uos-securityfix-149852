<?php foreach ($entity as $index => $subentity) : ?>
	<?php print render($subentity,$render->displaystring);?>
<?php endforeach; ?>