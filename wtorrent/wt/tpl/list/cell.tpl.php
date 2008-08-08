<div class="tbBulk" id="itab{$hash}">
	<div class="tbColTab"></div>
    {if $web->isHashChecking($hash) eq true}
        {assign var='bg_cell' value=$DIR_IMG|cat:'chash_bg.png'}
    {elseif $web->getTstate($hash) eq 'message'}
        {assign var='bg_cell' value=$DIR_IMG|cat:'error_bg.png'}
    {/if}
	<div class="tbCell" style="{if isset($bg_cell)} background-image: url({$bg_cell});{/if}" onmouseover="style.backgroundColor='#d5e991';" onmouseout="style.backgroundColor='#e5edf4'">	
		<div class="tbContentCell" style="width: 105px; padding-left: 0px; padding-top: 6px; height: 24px; text-align: left;">
			<input type="checkbox" id="{$hash}" class="torrent" style="margin: 0 0 5px 3px;" />
			{if $web->getState($hash) eq 0 || $web->getOpen($hash) eq 0}
                    <img src="{$DIR_IMG}bullet_go.png" onclick="command('start', '{$hash}');" alt="{$str.start}" title="{$str.start}" />
            {else}
                    <img src="{$DIR_IMG}cross.png"  onclick="command('stop', '{$hash}');" alt="{$str.stop}" title="{$str.stop}" />
            {/if}
            {if $web->getOpen($hash) neq 0}
                    <img src="{$DIR_IMG}lock_delete.png" onclick="command('close', '{$hash}');" alt="{$str.close}" title="{$str.close}" />
            {/if}
                    <img src="{$DIR_IMG}delete.png" onclick="command('erase', '{$hash}');" alt="{$str.erase}" title="{$str.erase}" />
            {if $web->isHashChecking($hash) neq true}
                    <img src="{$DIR_IMG}c_hash.png" onclick="command('chash', '{$hash}');" alt="{$str.chash}" title="{$str.chash}" />
            {/if}
		</div>
        <div class="tbContentCell" id="tip{$hash}" style="padding-top: 0px;">
        <div class="tbContentCell" style="width: 197px; text-align: left; padding-left: 10px;" onclick="resizeInnerTab('{$hash}');">
			{if $web->getState($hash) eq 1 && $web->getPercent($hash) neq 100}
				{assign var="color" value="green"}
			{/if}
			{if $web->getState($hash) eq 1 && $web->getPercent($hash) eq 100}
				{assign var="color" value="blue"}
			{/if}
			{if $web->getState($hash) eq 0 || $web->getOpen($hash) eq 0}
				{assign var="color" value="black"}
			{/if}
			<span style="color: {$color};">
                {$web->getName($hash)|truncate:35:"...":true}
                {if $web->isHashChecking($hash) eq true} 
                    [CHECKING HASH]
                {/if}
            </span>
		</div>
		<div class="tbContentCell" style="width: 55px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getTotalSeeds($hash)} ({$web->getConnSeeds($hash)})
		</div>
		<div class="tbContentCell" style="width: 55px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getTotalPeers($hash)} ({$web->getConnPeers($hash)})
		</div>
		<div class="tbContentCell" style="width: 82px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getDownRate($hash)}
		</div>
		<div class="tbContentCell" style="width: 82px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getUpRate($hash)}
		</div>
		<div class="tbContentCell" style="width: 61px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getDone($hash)}
		</div>
		<div class="tbContentCell" style="width: 55px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getSize($hash)}
		</div>
		<div class="tbContentCell" style="width: 82px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getPercent($hash)}%
		</div>
			{if $web->getRatio($hash) < 1}
				{assign var=ratio value="red"}
			{else}
				{assign var=ratio value="green"}
			{/if}
		<div class="tbContentCell" style="color: {$ratio}; width: 67px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getRatio($hash)}
		</div>
		<div class="tbContentCell" style="width: 60px;" onclick="resizeInnerTab('{$hash}');">
			{$web->getETA($hash)}
		</div>
        </div>
	</div>
</div>
<div class="tbBulk" id="ttab{$hash}" style="display: none; height: auto;">
	<div class="tbColTab" style="height: 125px;">{include file="tabsL.tpl.php" id=$clau hash=$hash}</div>
    <div id="tab{$hash}" style="border: 1px solid #d4d4d4; border-top-width: 0px; width: 912px; float: left; display: block; min-height: 125px;"></div>
</div>