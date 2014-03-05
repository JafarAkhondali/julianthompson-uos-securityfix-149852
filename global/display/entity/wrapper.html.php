<!-- start wrapper.html.php -->
<div id="<?php print $instanceid;?>" class="<?php print $render->classtreestring;?>" data-type="<?php print $render->entitytype;?>" data-display="<?php print $render->displaymode;?>" data-actions="add,displayup,displaydown,edit,remove,save,cancel" data-accept="*" data-childcount="<?php print $render->childcount;?>">
<div class="uos-header">
	<div class="field-icon">
		<span class="fa-stack fa-lg">
		  <!--<i class="fa fa-square-o fa-stack-2x"></i>-->
		  <i class="fa fa-<?php print $render->typeinfo->icon;?> fa-stack-1x"></i>
		</span>
	</div>
	<i class="fa fa-stack-2x children-count"><?php print $render->childcount;?></i>
	<div class="field-group field-group-info">
		<h2 class="field field-title"><?php print $entity->title->value;?></h2>
		<span class="field field-type"><?php print $render->typeinfo->title;?> (<?php print $entity->guid->value;?>)</span> 
	</div>
</div>
<?php print $content;?>
</div>
<script>uos.addelement('#<?php print $instanceid;?>',<?php print json_encode($render);?>);</script>
<pre>
<?php //print_r($render);?>
</pre>
<!-- end wrapper.html.php -->