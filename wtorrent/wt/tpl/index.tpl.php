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
	<script src="{$DIR_JS}control.js" type="text/javascript" charset="utf-8"></script>
	<script src="{$DIR_JS}ajax.js" type="text/javascript" charset="utf-8"></script>
	<script src="{$DIR_JS}func.js" type="text/javascript" charset="utf-8"></script>
  {include file="loading.tpl.php" assign="loading"}
  {include file="comm_loading.tpl.php" assign="comm_loading"}
	{if $web->isRegistered()}
		{literal}
			<script type="text/javascript">
			  <!--
				var confirm_chash = '{/literal}{$str.confirm_chash|escape:'javascript'}{literal}';
				var confirm_erase = '{/literal}{$str.confirm_erase|escape:'javascript'}{literal}';
				var no_torrents_selected = '{/literal}{$str.no_torrents_selected|escape:'javascript'}{literal}';
			  //-->
			</script>
		{/literal}
	{/if}
	<link rel="shortcut icon" type="image/x-icon" href="{$DIR_IMG}favicon.ico" />
</head>

<body>
{if $web->isRegistered()}
<div id="menu">
	{include file="menu.tpl.php" width_total="600"}
</div>
{/if}
{include file="messages.tpl.php"}
{if $web->isRegistered()}
	{include file="content.tpl.php"}
{else}
	{include file="login.tpl.php"}
{/if}
<div id="debug" style="width: 800px; height: 30px;"></div>
<div id="loadingMain" style="display: none;">
	{include file="loading.tpl.php"}
</div>
<div id="loadingMessages" style="display: none;">
  {include file="comm_loading.tpl.php"}
</div>
</body>

</html>
