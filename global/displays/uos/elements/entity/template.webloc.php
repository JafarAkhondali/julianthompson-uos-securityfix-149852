<?php //print '<'.'?xml version="1.0" encoding="UTF-8" ?'.'>'; ?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>URL</key>
	<string><?php print $uos->request->hosturl . (string) $entity->guid;?></string>
</dict>
</plist>