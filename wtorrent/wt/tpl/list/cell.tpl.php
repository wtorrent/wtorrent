{if $web->isHashChecking($hash) eq true}
    {assign var='class' value='chash'}
{elseif $web->getTstate($hash) eq 'message'}
    {assign var='class' value='error'}
{/if}
<div class="torrent{if isset($class)} {$class}{/if}" id="itab{$hash}" onmouseover="style.backgroundColor='#d5e991';" onmouseout="style.backgroundColor='#e5edf4'">
	<div id="tip{$hash}" class="clearfix">
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
		<div class="name {$color}" onclick="resizeInnerTab('{$hash}');">
			{$web->getName($hash)}
		</div>
		<div class="download" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_download}: {$web->getDownRate($hash)} {$str.tb_speed_unit}
		</div>
		<div class="upload" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_upload}:	{$web->getUpRate($hash)} {$str.tb_speed_unit}
		</div>
	</div>
	<div class="clearfix">
		<div class="buttons">
			{include file="list/buttons.tpl.php"}
		</div>
		<div class="percent" onclick="resizeInnerTab('{$hash}');">
			<div class="percentBar" style="width: {$web->getPercent($hash)}%;">
				
			</div>
		</div>
		<div class="seeds" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_seeds}: {$web->getTotalSeeds($hash)} ({$web->getConnSeeds($hash)})
		</div>
		<div class="peers" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_peers}: {$web->getTotalPeers($hash)} ({$web->getConnPeers($hash)})
		</div>
		<div class="done" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_done}: {$web->getDone($hash)}
		</div>
		<div class="size" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_size}: {$web->getSize($hash)}
		</div>
		<div class="ratio" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_ratio}: {$web->getRatio($hash)}
		</div>
		<div class="eta" onclick="resizeInnerTab('{$hash}');">
			{$str.tb_eta}: {$web->getETA($hash)}
		</div>
	</div>
</div>
<div class="tbBulk" id="ttab{$hash}" style="display: none; height: auto;">
	<div class="tbColTab" style="height: 125px;">{include file="tabsL.tpl.php" id=$clau hash=$hash}</div>
    <div id="tab{$hash}" style="border: 1px solid #d4d4d4; border-top-width: 0px; width: 914px; float: left; display: block; min-height: 125px;"></div>
</div>