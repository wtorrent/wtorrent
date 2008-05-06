<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>{$TITLE}</title>
  <script src="{$DIR_JS}" type="text/javascript"></script>
  <script src="{$DIR_JSPROTO}" type="text/javascript"></script>
  <script src="{$DIR_SCRIPTACULOUS}?load=effects" type="text/javascript"></script>
  <script src="{$DIR_PROTOTIP}" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="home/css/reset.css" media="all" />
  <link rel="stylesheet" type="text/css" href="{$DIR_CSS_ESTIL}" media="all" />
  {include file="loading.tpl.php" assign="loading"}
  {include file="comm_loading.tpl.php" assign="comm_loading"}
	{if $web->registrado()}
		{literal}
			<script type="text/javascript">
				/* Ajax loading using prototype */
				var ifPri = $('principal');
				var ifCont = $('contingut');
				var loading = "{/literal}{$loading|jsOutput}{literal}";
				var loadingCommand = "{/literal}{$comm_loading|jsOutput}{literal}";
				var cont;
				var tab;
				var view = 'public';
				var url = '{/literal}{$SRC_INDEX}{literal}';
				var confirmMsg = "{/literal}{$str.conf_erase}{literal}";
			</script>
		{/literal}
	{/if}
	<link rel="shortcut icon" href="{$DIR_IMG}favicon.ico">
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
<div class="tbBulk loadingTab" id="loadingCell" style="display: none; width: 918px;">
	<img src="{$DIR_IMG}miniloader.gif" alt="loading" />
</div>
</body>

</html>
