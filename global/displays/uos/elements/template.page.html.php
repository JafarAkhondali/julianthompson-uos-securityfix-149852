<div id="universetoolbars">

	<div id="universetoolbar-tags" class="universetoolbar uos-element-active">
			<div class="field uos-tags">
				<ul>
					<li class="universe-entity"><?php print $uos->request->universe->title;?></li><li>Julian</li></li><li>Work</li><li>Project</li><li>Policy Connect</li><li>&nbsp;<i class="fa fa-plus"></i>&nbsp</li>
				</ul>
				<div class="clearboth"></div>
			</div>
	</div>

	<div id="universetoolbar-status" class="universetoolbar uos-element-active">
		<div class="uos-element" id="uos-status-icon">
			<div class="uos-header">
				<div class="field-icon">
					<span class="fa-stack fa-lg">
					  <!--<i class="fa fa-square-o fa-stack-2x"></i>-->
					  <i class="fa fa-<?php print $render->entityconfig->icon;?> fa-stack-1x" id="uos-entity-icon"></i>
					</span>
				</div>
				<i class="fa fa-stack-2x children-count" id="universe-selected-count">0</i>
			</div>
		<</div>
		<div id="universetoolbar-tagbar">
			<div id="uos-entity-title"><?php print $uos->request->universe->title;?></div>
			<div id="uos-entity-type"><?php print $render->entityconfig->title;?></div>
		</div>
		<div class="clearboth"></div>
	</div>

	<div id="universetoolbar-actions" class="universetoolbar uos-element-active">
		<ul id="universe-actions" class="uos-actions"></ul>
		<div class="clearboth"></div>
	</div>
	
</div>

<div id="container">
<?php print rendernew($entity['content'],'html');?>
</div>

<div id="dialog">
<?php //print_r($universe);?>
</div>

<div id="input">
	<h2><i class="fa fa-sign-in"></i> Request</h2>
	<?php //print render($uos->request);?>
	<h2><i class="fa fa-sign-in"></i> Log</h2>
	<div id="inputmessages">
	<?php //print render($uos->output['log']); ?>
	</div>
	<h2><i class="fa fa-sign-in"></i> Universe Config ($uos->config)</h2>
	<div id="uosobject">
	<?php //print render($uos->config);?>
	</div>
</div>	