<form action="/global/uos.php" method="post" class="uos-action-form">
<?php print render($entity->properties,'edit.html'); ?>
<div class="uos-form-controls">
	<input type="submit" name="submit" value="Submit" class="btn btn-default">
	<input type="button" name="cancel" value="Cancel" class="btn btn-default">
</div>
</form>
<?php //print render($entity->getactions()); ?>
<div class="clearboth"></div>	