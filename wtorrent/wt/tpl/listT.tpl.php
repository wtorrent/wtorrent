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
		<div id="torrentListButtons" style="clear: both; height: 25px; text-align: left; margin-left: 43px; margin-top: 3px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
			<select id="actions">
				<option value="0">{$str.stop}</option>
				<option value="4">{$str.closet}</option>
				<option value="1">{$str.start}</option>
				<option value="2">{$str.erase}</option>
				<option value="3">{$str.chash}</option>
			</select>
			<div class="but_bottom torrentAction"> {$str.action} </div> 
			<div class="but_bottom torrentCheckAll"> {$str.check_all} </div>
			<div class="but_bottom torrentUncheckAll"> {$str.uncheck_all} </div>
			<div class="but_bottom torrentInvertAll"> {$str.invert_all} </div>
		</div>
	{/if}
{foreachelse}
	<div class="noTorrents">{$str.no_torrents}</div>
{/foreach}
{foreach item=hash from=$hashes}
	{include file="listT/tooltip/tip.tpl.php"}
{/foreach}
{literal}
	<script type="text/javascript">
	<!--
		var control;
		function postAjax() {
			if(control == undefined)
				control = new Control({/literal}{$EFFECTS}{literal});
			/* Render the tips */
			var tips = $$('.torrent');
			for(var i = 0; i < tips.length; i++)
			{		
				torrentTip(tips[i].id);
			}
		}
		document.observe("dom:loaded", function(){
			postAjax();
		})
	//-->
	</script>
{/literal}