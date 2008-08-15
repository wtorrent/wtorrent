{if $web->isHashChecking($hash) eq true}
    {assign var='class' value='chash'}
{elseif $web->getTstate($hash) eq 'message'}
    {assign var='class' value='error'}
{/if}
<div class="torrent{if isset($class)} {$class}{/if}" id="{$hash}">
	<table id="tip{$hash}" class="download">
		<tr>
			{* DEPRECATED, has to be done otherway *}
			{if $web->getState($hash) eq 1 && $web->getPercent($hash) neq 100}
				{assign var="color" value="green"}
			{/if}
			{if $web->getState($hash) eq 1 && $web->getPercent($hash) eq 100}
				{assign var="color" value="blue"}
			{/if}
			{if $web->getState($hash) eq 0 || $web->getOpen($hash) eq 0}
				{assign var="color" value="black"}
			{/if}

			<th class="name {$color}" colspan="4">
				{$web->getName($hash)|truncate:100:"...":true}
			</th>
			<td class="downrate">
				{$str.tb_download}: <span>{$web->getDownRate($hash)} {$str.tb_speed_unit}</span>
			</td>
			<td class="uprate">
				{$str.tb_upload}: <span>{$web->getUpRate($hash)} {$str.tb_speed_unit}</span>
			</td>
		</tr>
		<tr>
			<td class="buttons">
				{include file="list/buttons.tpl.php"}
			</td>
			<td class="percentcont">
				<div class="percenttext">{$web->getPercent($hash)}%</div>
				<div class="percent">
					<div class="percentBar" style="width: {$web->getPercent($hash)}%;"></div>
				</div>
			</td>
			<td class="seedspeers">
				<span title="{$str.tb_seeds}">{$web->getTotalSeeds($hash)} ({$web->getConnSeeds($hash)})</span>
				/ <span title="{$str.tb_peers}">{$web->getTotalPeers($hash)} ({$web->getConnPeers($hash)})</span>
			</td>
			<td class="transfer">
				<span title="{$str.tb_done}">{$web->getDone($hash)}</span>
				/ <span title="{$str.tb_size}">{$web->getSize($hash)}</span>
			</td>
			{if $web->getRatio($hash) < 1}
				{assign var=ratio value="red"}
			{else}
				{assign var=ratio value="green"}
			{/if}
			<td class="ratio">
				{$str.tb_ratio}: <span class="{$ratio}">{$web->getRatio($hash)}</span>
			</td>
			<td class="eta">
				{$str.tb_eta}: <span>{$web->getETA($hash)}</span>
			</td>
		</tr>
	</table>
</div>
<div class="tbBulk" id="ttab{$hash}" style="display: none; height: auto;">
	<div class="tbColTab" style="height: 125px;">{include file="tabsL.tpl.php" id=$clau hash=$hash}</div>
	<div id="tab{$hash}" style="border: 1px solid #d4d4d4; border-top-width: 0px; width: 914px; float: left; display: block; min-height: 125px;"></div>
</div>
