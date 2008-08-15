<div id="main_t">
{if $web->getTplName() eq listT}
	{include file="tabs.tpl.php"}
{/if}	
</div>

<div id="main_m">
	<div id="content">
		{include file=$web->getTpl()}
	</div>
</div>
<div id="main_b">
	
</div>
{if $web->getTplName() eq listT}
<div id="refresh">
	
</div>
{/if}
{literal}
<script type="text/javascript">
<!--
	$('close_m').onclick = (function (frame) { return function () { $(frame).hide(); } })('messages_box');
//-->
</script>
{/literal}