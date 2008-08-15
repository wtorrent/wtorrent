<div style="width: 100%; font-family: arial; font-size: 11px; padding-left: 10px; padding-top: 10px; padding-bottom: 5px; text-align: left;">
			<b style="color: #4C8CC4;">{$str.torrent_file}:</b> {$web->getTorrent()}
	</div>
	<div style="width: 100%; font-family: arial; font-size: 11px; padding-left: 10px; text-align: left;">
			<b style="color: #4C8CC4;">{$str.data_path}:</b> {$web->getDataPath()}
	</div>
	<div style="height: 18px; width: 700px; margin-left: auto; margin-right: auto; margin-top: 10px;">
		<div style="width: 171px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
			<b style="color: #4C8CC4;">{$str.tb_done}: </b>{$web->getDone()}
		</div>
		<div style="width: 171px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
			<b style="color: #4C8CC4;">{$str.tb_uploaded}: </b>{$web->getUp()}
		</div>
		<div style="width: 171px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
                        <b style="color: #4C8CC4;">{$str.tb_size}: </b>{$web->getSize()}
                </div>
		 <div style="width: 171px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
                        <b style="color: #4C8CC4;">{$str.tb_ratio}: </b>{$web->getRatio()}
                </div>
	</div>
	<div style="width: 700px; font-family: arial; font-size: 11px; padding: 2px; height: 15px; margin-top: 10px; border: 1px solid #d4d4d4; margin-left: auto; margin-right: auto;">
			<div style="width: {$web->getPercent()}%;background-color: #4C8CC4; height: 15px; text-align: center; color: #d4d4d4; font-weight: bold;">{$web->getPercent()}%</div>
	</div>
	<div style="height: 18px; width: 700px; margin-left: auto; margin-right: auto; margin-top: 10px;">
		<div style="width: 171px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
			<b style="color: #4C8CC4;">{$str.max_peers}: </b>{$web->getMaxPeers()}
		</div>
		<div style="width: 171px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
			<b style="color: #4C8CC4;">{$str.min_peers}: </b>{$web->getMinPeers()}
		</div>
		<div style="width: 342px; float: left; font-family: arial; font-size: 11px; padding: 2px;">
                        <form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}&tpl=details&hash={$web->getHash()}">
						<b style="color: #4C8CC4;">{$str.priority}: </b>
                        {assign var="priority" value=$web->getPriority()}
                        {foreach from=$web->getPriorities() item="pr" key="prKey" name="priorities"}
                        	{if $smarty.foreach.priorities.first}
                        		<select id="sp{$web->getHash()}">
                        	{/if}
                        	<option value="{$prKey}"{if $priority eq $prKey} selected="selected"{/if}>{$pr}</option>
                        	{if $smarty.foreach.priorities.last}
                        		</select>
                        	{/if}
                        {/foreach}
                        <div class="but_bottom priority"> {$str.change_pr} </div></div>
                        </form>
                </div>
	</div>
	<br />
	{if $web->getMessage() neq ''}
	<div style="width: 100%; font-family: arial; font-size: 11px; padding: 2px;">
			<b style="color: #4C8CC4;">{$str.rtorrent_message}:</b> {$web->getMessage()}
	</div>
	{/if}