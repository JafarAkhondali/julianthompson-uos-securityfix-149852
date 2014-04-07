<?php foreach($entity as $fieldname => $value) : ?>
<?php print $fieldname;?> : <?php print rendernew($value);?> (<?php print (gettype($value));?>)<?php print PHP_EOL;?>
<?php endforeach; ?>
