		{if $web->getView() eq 'public'}
            {assign var='hashes' value=$web->getPublicHashes()}
        {else}
            {assign var='hashes' value=$web->getPrivateHashes()}
        {/if}
        
		{foreach key=clau item=hash from=$hashes name="listT"}
        	{if $smarty.foreach.listT.first}
        		{include file="list/tableHead.tpl.php"}
        	{/if}
        	{include file="list/cell.tpl.php" hash=$hash clau=$clau}
        	{if $smarty.foreach.listT.last}
        	<div style="clear: both; height: 25px; text-align: left; margin-left: 43px; margin-top: 3px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
				<select id="actions">
					<option value="0">{$str.stop}</option>
		                        <option value="4">{$str.closet}</option>
					<option value="1">{$str.start}</option>
					<option value="2">{$str.erase}</option>
		                        <option value="3">{$str.chash}</option>
				</select>
				<div class="but_bottom" onclick="command('batch','');"> {$str.action} </div> 
				<div class="but_bottom" onclick="checkAllByClass('torrent');"> {$str.check_all} </div>
				<div class="but_bottom" onclick="uncheckAllByClass('torrent');"> {$str.uncheck_all} </div>
				<div class="but_bottom" onclick="invertAllByClass('torrent');"> {$str.invert_all} </div>
			</div>
			{/if}
        {foreachelse}
        	<div class="noTorrents">{$str.no_torrents}</div>
        {/foreach}
{foreach item=hash from=$hashes}
    {include file="listT/tooltip/tip.tpl.php"}
{/foreach}
{literal}
<script language="javascript" type="text/javascript">
function postAjax() {
    /* Render the lateral torrents tabs */
	var tabsL = document.getElementsByClassName('tabsLeft');
	for (var i=0; i < tabsL.length; i++) {
		var tabs = tabsL[i].getElementsByTagName('li');
		for(var j = 0; j < tabs.length; j++)
			tabL.render(tabs[j]);
	}
    /* Render the tips */
    var tips = $$('.torrent');
    for(var i = 0; i < tips.length; i++)
    {
        torrentTip(tips[i].id);
    }
}
postAjax();
</script>
{/literal}