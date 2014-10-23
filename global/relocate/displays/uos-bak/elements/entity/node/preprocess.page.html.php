<?php
// node preprocess - /entity/node/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/preprocess.page.html.php';

$render->title = (string) $entity->title;
