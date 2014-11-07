<?php
if ($this->mime->value=='image/png') {
	echo "file is 'image/png' just make a link or output file";
} else {
	echo 'this will write out "' . $this->mime . '" as "image/png".'; 
}