		{if $web->getView() eq 'public'}
		{foreach key=clau item=hash from=$web->getPublicHashes() name="listT"}
        	{if $smarty.foreach.listT.first}
        		{include file="list/tableHead.tpl.php"}
        	{/if}
        	{include file="list/cell.tpl.php" hash=$hash clau=$clau}
        	{if $smarty.foreach.listT.last}

        	{/if}
        {foreachelse}
        	<div class="noTorrents">{$str.no_torrents}</div>
        {/foreach}
        {/if}
        {if $web->getView() eq 'private'}
        {foreach key=clau item=hash from=$web->getPrivateHashes() name="listT"}
        	{if $smarty.foreach.listT.first}
        		{include file="list/tableHead.tpl.php"}
        	{/if}
        	{include file="list/cell.tpl.php" hash=$hash clau=$clau}
        	{if $smarty.foreach.listT.last}

        	{/if}
        {foreachelse}
        	<div class="noTorrents">{$str.no_torrents}</div>
        {/foreach}
        {/if}
        {literal}
<script language="javascript" type="text/javascript">
    function postAjax() {
    	var TabsL = document.getElementsByClassName('tabsLeft');
    	var numTabsL = TabsL.length;
    	for(var j=0; j< numTabsL; j++) {
    		var tabsL = (document.getElementById) ? document.getElementById('tabsL' + j) : eval("document.all['tabsL" + j + "']");
    		var tabs = tabsL.getElementsByTagName("li");
    		for (var i=0; i < tabs.length; ++i) {
      		tabL.render(tabs[i]);
    		}
    	}
    	init();
    }
	function files() {
		var files = document.getElementsByClassName('but_files');
			for (var i = 0; i < files.length; i++) {
			//$(erase[i].id).onclick = function () {
			files[i].onclick = function () {
				command('files', this.id);
			}
		}
		var files = document.getElementsByClassName('but_fcheck');
			for (var i = 0; i < files.length; i++) {
			//$(erase[i].id).onclick = function () {
			files[i].onclick = function () {
				var file = document.getElementsByClassName('files' + this.id);
				for (var i = 0; i < file.length; i++) {
					file[i].checked = true;
				}
			}
		}
		var files = document.getElementsByClassName('but_funcheck');
			for (var i = 0; i < files.length; i++) {
			//$(erase[i].id).onclick = function () {
			files[i].onclick = function () {
				var file = document.getElementsByClassName('files' + this.id);
				for (var i = 0; i < file.length; i++) {
					file[i].checked = false;
				}
			}
		}
	}
	function info() {
		var info = document.getElementsByClassName('but_info');
			for (var i = 0; i < info.length; i++) {
			//$(erase[i].id).onclick = function () {
			info[i].onclick = function () {
				command('info', this.id);
			}
		}
	}
	function trackers() {
		var trackers = document.getElementsByClassName('but_trackers');
			for (var i = 0; i <trackers.length; i++) {
			//$(erase[i].id).onclick = function () {
			trackers[i].onclick = function () {
				command('trackers', this.id);
			}
		}
		var trackers = document.getElementsByClassName('but_tcheck');
			for (var i = 0; i < trackers.length; i++) {
			//$(erase[i].id).onclick = function () {
			trackers[i].onclick = function () {
				var tracker = document.getElementsByClassName('trackers' + this.id);
				for (var i = 0; i < tracker.length; i++) {
					tracker[i].checked = true;
				}
			}
		}
		var trackers = document.getElementsByClassName('but_tuncheck');
			for (var i = 0; i < trackers.length; i++) {
			//$(erase[i].id).onclick = function () {
			trackers[i].onclick = function () {
				var tracker = document.getElementsByClassName('trackers' + this.id);
				for (var i = 0; i < tracker.length; i++) {
					tracker[i].checked = false;
				}
			}
		}
	}
</script>
{/literal}