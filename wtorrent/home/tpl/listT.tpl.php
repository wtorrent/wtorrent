		{if $web->getView() eq 'public'}
		{foreach key=clau item=hash from=$web->getPublicHashes() name="listT"}
        	{if $smarty.foreach.listT.first}
        		{include file="list/tableHead.tpl.php"}
        	{/if}
        	{include file="list/cell.tpl.php" hash=$hash clau=$clau}
        	{if $smarty.foreach.listT.last}
        	<div style="clear: both; height: 25px; text-align: left; margin-left: 43px; margin-top: 3px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
				<select id="actions">
					<option value="0">{$str.stop}</option>
					<option value="1">{$str.start}</option>
					<option value="2">{$str.erase}</option>
				</select>
				<div class="but_files" onclick="command('batch','');"> {$str.action} </div> 
				<div class="but_check" onclick="torcheck_all();"> {$str.check_all} </div>
				<div class="but_uncheck" onclick="toruncheck_all();"> {$str.uncheck_all} </div>
			</div>
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
			<div style="clear: both; height: 25px; text-align: left; margin-left: 43px; margin-top: 3px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
				<select id="actions">
					<option value="0">{$str.stop}</option>
					<option value="1">{$str.start}</option>
					<option value="2">{$str.erase}</option>
				</select>
				<div class="but_files" onclick="command('batch','');"> {$str.action} </div> 
				<div class="but_check" onclick="torcheck_all();"> {$str.check_all} </div>
				<div class="but_uncheck" onclick="toruncheck_all();"> {$str.uncheck_all} </div>
			</div>
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
	function torcheck_all()
	{ 
		var torrents = document.getElementsByClassName('torrent');
		for (var i = 0; i < torrents.length; i++) {
			torrents[i].checked = true;
		}
	}
	function toruncheck_all()
	{
		var torrents = document.getElementsByClassName('torrent');
		for (var i = 0; i < torrents.length; i++) {
			torrents[i].checked = false;
		}
	}
</script>
{/literal}