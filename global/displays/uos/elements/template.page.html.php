<?php
if (isset($entity['content'])) {
	$content = $entity['content'];
} 
$entitytitle = (isset($content->title)) ? $content->title : 'Not known';
?>
<div id="universetoolbars">

	<div id="universetoolbar-tags" class="universetoolbar uos-element-active">
			<div class="field uos-tags">
				<ul>
					<li class="universe-entity"><?php print $uos->request->universe->title;?></li>
					<li>Julian</li>
					<li>Work</li>
					<li>Project</li>
					<li>Policy Connect</li>
					<li><i class="fa fa-plus"></i></li>
				</ul>
				<div class="clearboth"></div>
			</div>
	</div>

	<div id="universetoolbar-status" class="universetoolbar uos-element-active">
		<div class="uos-element" id="uos-status-icon">
			<div class="field-icon">
				<span class="fa-stack">
				  <i class="fa fa-<?php print $render->entityconfig->icon;?> uos-entity-icon" id="uos-entity-icon"></i>
				</span>
			</div>
			<i class="fa fa-stack-2x children-count" id="universe-selected-count">0</i>
		</div>
		<div id="universetoolbar-tagbar">
			<div id="uos-entity-title"><?php print $entitytitle;?></div>
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
<?php print render($content,'html');?>
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

<a href="/cache/epsom.universeos.net/trace.log" target="_blank">trace</a>