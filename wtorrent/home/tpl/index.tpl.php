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
var ifPri = (document.getElementById) ? document.getElementById('principal') : document.all['principal'];
var ifCont = (document.getElementById) ? document.getElementById('contingut') : document.all['contingut'];
var cont;
var tab;
var view = 'public';
var url = '{/literal}{$SRC_INDEX}{literal}';
function init () {
	var tabs = document.getElementsByClassName('tabs');
	for (var i = 0; i < tabs.length; i++) {
		$(tabs[i].id).onclick = function () {
			getTabData(this.id);
		}
	}
	var tabsL = document.getElementsByClassName('tabsL');
	for (var i = 0; i < tabsL.length; i++) {
		$(tabsL[i].id).onclick = function () {
			getTabLData(this.id);
		}
	}
	var start = document.getElementsByClassName('start');
	for (var i = 0; i < start.length; i++) {
		start[i].onclick = function () {
			command('start', this.id);
		}
	}
	var stop = document.getElementsByClassName('stop');
	for (var i = 0; i < stop.length; i++) {
		stop[i].onclick = function () {
			command('stop', this.id);
		}
	}
	var erase = document.getElementsByClassName('erase');
	for (var i = 0; i < erase.length; i++) {
		//$(erase[i].id).onclick = function () {
		erase[i].onclick = function () {
			command('erase', this.id);
		}
	}
}
function getTabData(id) {
	if(id == 'public') {
		var pars = 'cls=ListT&tpl=ajax&view=public';
		view = 'public';
		tab = 1;
	}
	if(id == 'private') {
		var pars = 'cls=ListT&tpl=ajax&view=private';
		view = 'private';
		tab = 1;
	}
	var myAjax = new Ajax.Request( url, {
		method: 'get', 
		parameters: pars, 
		onLoading: showLoad, 
		onComplete: showResponse
	} );	
}
function getTabLData(id) {
	cont = id;
	var cls;
	switch(id.substr(0,1))
	{
		case 'i':
			cls = 'General';
			break;
		case 'f':
			cls = 'Files';
			break;
		case 't':	
			cls = 'Tracker';
			break;
		default:
			break;
	}
	tab = 2;
	var pars = 'cls=' + cls + '&tpl=details&hash=' + id.substr(4);
	var myAjax = new Ajax.Request( url, {
		method: 'get', 
		parameters: pars, 
		onLoading: showLoadT, 
		onComplete: showResponseT
	} );
}
function command(command, param)
{
	if(command == 'files')
	{
		var params = [];
		//$('debug').innerHTML = '';
		var files = document.getElementsByClassName('files' + param);
		for (var i = 0; i < files.length; i++) {
			if(files[i].checked === true)
				params.push(files[i].id);
		}
		var param2 = params.join('~');     
		param2 = encodeURIComponent(param2);
		param1 = $('sf' + param).options[$('sf' + param).selectedIndex].value;
 		//$('debug').innerHTML = 'par: ' + param + ' par1: ' + param1 + ' par2: ' + param2;
 		var pars = 'cls=commands&tpl=commands&command=' + command + '&param=' + param + '&param1=' + param1 + '&param2=' + param2;
 		var myAjax = new Ajax.Request( url, {
			method: 'get', 
			parameters: pars, 
			onLoading: showLoadM, 
			onComplete: showResponseM
		} );
		getTabLData('ftab' + param);
		
	} else if(command == 'info') {
		
		param1 = $('sp' + param).options[$('sp' + param).selectedIndex].value;
		var pars = 'cls=commands&tpl=commands&command=' + command + '&param=' + param + '&param1=' + param1;
		var myAjax = new Ajax.Request( url, {
			method: 'get', 
			parameters: pars, 
			onLoading: showLoadM, 
			onComplete: showResponseM
		} );
		getTabLData('itab' + param);
		
	} else if(command == 'trackers') {
		
		var params = [];
		//$('debug').innerHTML = '';
		var trackers = document.getElementsByClassName('trackers' + param);
		for (var i = 0; i < trackers.length; i++) {
			if(trackers[i].checked === true)
				params.push(trackers[i].id);
		}
		var param2 = params.join('~');     
		param2 = encodeURIComponent(param2);
		param1 = $('st' + param).options[$('st' + param).selectedIndex].value;
 		//$('debug').innerHTML = 'par: ' + param + ' par1: ' + param1 + ' par2: ' + param2;
 		var pars = 'cls=commands&tpl=commands&command=' + command + '&param=' + param + '&param1=' + param1 + '&param2=' + param2;
 		var myAjax = new Ajax.Request( url, {
			method: 'get', 
			parameters: pars, 
			onLoading: showLoadM, 
			onComplete: showResponseM
		} );
		getTabLData('ttab' + param);
		
	} else {
		if(command == 'erase')
		{
			if(!confirm("{/literal}{$str.conf_erase}{literal}"))
				return;
		}
		var pars = 'cls=commands&tpl=commands&command=' + command + '&param=' + param;
		var myAjax = new Ajax.Request( url, {
			method: 'get', 
			parameters: pars, 
			onLoading: showLoadM, 
			onComplete: showResponseM
		} );
		getTabData(view);
	}
}
function showLoadT () {
	/*$('load').style.display = 'block';
	$('debug').innerHTML = cont.slice(1) + ' - ' + cont.match("tab");*/
	$(cont.slice(1)).innerHTML = "{/literal}{$loading|jsOutput}{literal}";
}
function showResponseT (originalRequest) {
	var newData = originalRequest.responseText;
	/*$('load').style.display = 'none';
	$('debug').innerHTML = 'resp - '+ cont.slice(1) + ' - ' + cont.match("tab")+ newData;*/
	var tab = $(cont.slice(1));
	tab.innerHTML = newData;
	if(cont.substr(0,1) == 'f')
		files();
	if(cont.substr(0,1) == 'i')
		info();
	if(cont.substr(0,1) == 't')
		trackers();
	/*var offset = $('c' + cont.slice(1)).offsetHeight - 100;
	var height = $(cont.slice(1)).offsetHeight + offset;
	$(cont.slice(1)).style.height = $('c' + cont.slice(1)).offsetHeight + "px";
	//$(cont.slice(1)).style.height = "";
	$('debug').innerHTML = ifCont.offsetHeight + " + " + offset;*/
	/*ifCont.style.height = ifCont.offsetHeight + offset + "px";*/
	/*ifCont.style.height = "";
	var display = ifPri.style.display;
	ifPri.style.display = 'none';
	ifPri.style.display = display;*/
	resize();
}
function showLoad () {
	/*$('load').style.display = 'block';*/
	$('contingut').innerHTML = "{/literal}{$loading|jsOutput}{literal}";
	/*$('debug').innerHTML = 'load';*/
}
function showResponse (originalRequest) {
	var newData = originalRequest.responseText;
	/*$('debug').innerHTML = 'resp';
	$('load').style.display = 'none';*/
	//$('debug').innerHTML = 'resp - '+ cont.slice(1) + ' - ' + cont.match("tab")+ newData;
	$('contingut').innerHTML = newData;
	postAjax();
	/*var offset = $('c' + cont.slice(1)).offsetHeight - 150;
	var height = $(cont.slice(1)).offsetHeight + offset;
	$(cont.slice(1)).style.height = $('c' + cont.slice(1)).offsetHeight + "px";
	//$(cont.slice(1)).style.height = "";
	$('debug').innerHTML = ifCont.offsetHeight + " + " + offset;
	ifCont.style.height = ifCont.offsetHeight + offset + "px";*/
	resize();
	
}
function showLoadM () {
	/*$('load').style.display = 'block';*/
	$('messages').style.display = "block";
	$('messages').innerHTML = "{/literal}{$comm_loading|jsOutput}{literal}";
	/*$('debug').innerHTML = 'load';*/
}
function showResponseM (originalRequest) {
	var newData = originalRequest.responseText;
	/*$('debug').innerHTML = 'resp';
	$('load').style.display = 'none';*/
	//$('debug').innerHTML = 'resp - '+ cont.slice(1) + ' - ' + cont.match("tab")+ newData;
	$('messages').innerHTML = newData;
	/*postAjax();
	var offset = $('c' + cont.slice(1)).offsetHeight - 150;
	var height = $(cont.slice(1)).offsetHeight + offset;
	$(cont.slice(1)).style.height = $('c' + cont.slice(1)).offsetHeight + "px";
	//$(cont.slice(1)).style.height = "";
	$('debug').innerHTML = ifCont.offsetHeight + " + " + offset;
	ifCont.style.height = ifCont.offsetHeight + offset + "px";
	resize();*/
	
}
function resize() {
	ifCont.style.height = "auto";
	ifCont.style.display = "none";
	ifCont.style.display = "";
	var display = ifPri.style.display;
	ifPri.style.height = "auto";
	ifPri.style.display = 'none';
	ifPri.style.display = display;	
}
</script>
{/literal}
{/if}
</head>

<body{if $web->registrado()} onload="init()"{/if}>
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
