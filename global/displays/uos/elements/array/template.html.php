<?php foreach ($entity as $index => $subentity) : ?>
<!-- array index <?php print $index;?> start -->
	<?php print rendernew($subentity,$render->displaystring);?>
	<!-- array index <?php print $index;?> end -->
<?php endforeach; ?>