<!-- Array xxx start -->
<?php $indexes = 1;?>
<ul class="array" data-type="array">
<?php foreach ($entity as $index => $subentity) : ?>
	<li class="array-row" id="array-<?php print $index;?>-row-<?php print $indexes;?>"><?php print render($subentity);?></li>
	<?php $indexes++;?>
<?php endforeach; ?>
</ul>
<!-- Array xxx end -->