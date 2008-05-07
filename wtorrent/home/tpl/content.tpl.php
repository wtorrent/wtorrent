<div id="main_t">
	{include file="tabs.tpl.php"}	
</div>
<div id="main_m">
	<div id="content">
		{include file=$web->getTpl()}
	</div>
</div>
<div id="main_b">
	
</div>
{if $web->getTplName() eq listT}
<div id="refresh" onclick="refresh();">
	
</div>
{/if}
{literal}
<script language="javascript" type="text/javascript">
	$('close_m').onclick = (function (frame) { return function () { $(frame).hide(); } })('messages_box');
</script>
{/literal}