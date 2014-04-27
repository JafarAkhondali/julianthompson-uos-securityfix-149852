<?php

include $render->rendererpath . '/elements/entity/preprocess.html.php';

foreach($entity->properties as $child) {
	$child->displaystring = 'edit.html';
}