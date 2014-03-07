<?php foreach($entity as $fieldname => $value) : ?>
<tr class="key-<?php print $fieldname;?>">
	<td class="fieldname"><?php print $fieldname;?></td>
	<td class="fieldvalue"><?php print_r($value);?></td>
</tr>
<?php endforeach; ?>