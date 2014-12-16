<?php header("Content-type: text/xml; charset=utf-8");?>
<?php print "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";?>
<?php if (isset($uos->output['resources']['xsl'])) : ?>
<?php print "<?xml-stylesheet type=\"text/xsl\" href=\"". $uos->output['resources']['xsl'][0] . "\" ?>\n";?>
<?php endif; ?>
<<?php print $render->wrapperelement;?>>
<?php print $render->templateoutput;?>
</<?php print $render->wrapperelement;?>>