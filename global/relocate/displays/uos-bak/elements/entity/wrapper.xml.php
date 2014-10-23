<?xml version="1.0" encoding="UTF-8"?>
<?php if (isset($uos->output['resources']['xsl'])) : ?>
<?xml-stylesheet type="text/xsl" href="<?php print $uos->output['resources']['xsl'][0];?>"?>
<?php endif; ?>

<<?php print $render->wrapperelement;?>>
<?php print $render->templateoutput;?>

</<?php print $render->wrapperelement;?>>