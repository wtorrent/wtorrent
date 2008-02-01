<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>{$TITLE}</title>
  <script src="{$DIR_JS}" type="text/javascript"></script>
  <script src="{$DIR_JSHADE}" type="text/javascript"></script>
  <script src="{$DIR_JSPROTO}" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="{$DIR_CSS_ESTIL}" media="all" />
  {include file="loading.tpl.php" assign="loading"}
  {include file="comm_loading.tpl.php" assign="comm_loading"}
  {literal}
  <script language="javascript" type="text/javascript">
	/* For shadowed Borders*/
	var tabBorder = RUZEE.ShadedBorder.create({ corner:8, edges:"tlr", border:1, shadow:16});
    var Bora = RUZEE.ShadedBorder.create({ corner:8, shadow:16, border: 1 });
    var Main = RUZEE.ShadedBorder.create({ corner:8, shadow:16, border: 1 });
    var tabL = RUZEE.ShadedBorder.create({ corner:4, edges:"tbl", border:1});
  
  var partialIDs = ["tl", "tr", "bl", "br", "tlr", "blr", "tbl", "tbr"];
  var partialBorders = {};
  for (var i=0; i<partialIDs.length; ++i) {
    partialBorders[partialIDs[i]] = RUZEE.ShadedBorder.create({
        corner:10, border:2, edges:partialIDs[i] });
  }
</script>
{/literal}
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
/*function init () {
	var tabs = document.getElementsByClassName('tabs');
	for (var i = 0; i < tabs.length; i++) {
		$(tabs[i].id).onclick = function () {
			load('content' , this.id);
		}
	}
	var tabsL = document.getElementsByClassName('tabsL');
	for (var i = 0; i < tabsL.length; i++) {
		$(tabsL[i].id).onclick = function () {
			load(this.id);
		}
	}
}*/
</script>
{/literal}
{/if}
</head>

<body>
<div style="width: 900px; height: 100px;position: relative; margin-left: auto; margin-right: auto;">
	<div style="position: absolute; left: 150px; margin: 0px auto; width: 600px; height: 100px; top: 0px;">
		<img src="{$DIR_IMG}wLogo.png" />
	</div>
</div>
{if $web->registrado()}
<div id="menu">
	{include file="menu.tpl.php" width_total="600"}
</div>
{/if}
{include file="messages.tpl.php"}
{if $web->registrado()}
	{include file="tabs.tpl.php"}	
	{include file="content.tpl.php"}
{else}
	{include file="login.tpl.php"}
{/if}
<div id="debug" style="width: 800px; height: 30px;"></div>
<div style="display: none;">
{include file="comm_loading.tpl.php"}
{include file="loading.tpl.php"}
</div>
</body>

</html>
