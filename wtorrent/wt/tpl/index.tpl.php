<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>{$TITLE}</title>
	<link rel="stylesheet" href="{$DIR_CSS}reset.css" type="text/css" media="all" charset="utf8" />
	<link rel="stylesheet" href="{$DIR_CSS}estil.css" type="text/css" media="all" charset="utf8" />
	<script src="{$DIR_JS}prototype.js" type="text/javascript" charset="utf-8"></script>
	<script src="{$DIR_JS}scriptaculous/scriptaculous.js?load=effects" type="text/javascript" charset="utf-8"></script>
	<script src="{$DIR_JS}prototip.js" type="text/javascript" charset="utf-8"></script>
	<script src="{$DIR_JS}javasc.js" type="text/javascript" charset="utf-8"></script>
  {include file="loading.tpl.php" assign="loading"}
  {include file="comm_loading.tpl.php" assign="comm_loading"}
	{if $web->registrado()}
		{literal}
			<script type="text/javascript">
			  <!--
				/* Ajax loading using prototype */
				var ifPri = $('principal');
				var ifCont = $('contingut');
				var loading = '{/literal}{$loading}{literal}';
				var loadingCommand = '{/literal}{$comm_loading}{literal}';
				var cont;
				var tab;
				var view = 'public';
				var url = '{/literal}{$SRC_INDEX}{literal}';
				var confirmMsg = '{/literal}{$str.conf_erase}{literal}';
			  //-->
			</script>
		{/literal}
	{/if}
	<link rel="shortcut icon" type="image/x-icon" href="{$DIR_IMG}favicon.ico" />
</head>

<body>
{if $web->registrado()}
<div id="menu">
	{include file="menu.tpl.php" width_total="600"}
</div>
{/if}
{include file="messages.tpl.php"}
{if $web->registrado()}
	{include file="content.tpl.php"}
{else}
	{include file="login.tpl.php"}
{/if}
<div id="debug" style="width: 800px; height: 30px;"></div>
<div style="display: none;">
{include file="comm_loading.tpl.php"}
{include file="loading.tpl.php"}
</div>
<div class="loadingCell" id="loadingCell" style="display: none;">
	<img src="{$DIR_IMG}miniloader.gif" alt="loading" />
</div>
</body>

</html>
