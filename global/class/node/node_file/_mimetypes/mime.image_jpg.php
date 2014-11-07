<?php
if ($this->mime->value=='image/jpeg') {
	echo "file is jpg just make a link or output file.";
} else {
	echo 'this will write out "' . $this->mime . '" as "image/jpeg".'; 
}