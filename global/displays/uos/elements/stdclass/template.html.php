<?php foreach($entity as $fieldname => $value) : ?>
<tr class="key-<?php print $fieldname;?>">
	<td class="fieldname"><?php print $fieldname;?></td>
	<td class="fieldvalue"><?php print render($value,$render->displaystring);?></td>
</tr>
<?php print_r($render);?>
<?php endforeach; ?>
xxxxxv