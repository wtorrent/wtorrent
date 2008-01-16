<div id="tbBulk">
	<div id="tbColTab"></div>
	<div id="tbCell" style="cursor: pointer; background-color: #e5edf4; border-top: 0px solid #d4d4d4;" onmouseover="style.backgroundColor='#d5e991';" onmouseout="style.backgroundColor='#e5edf4'">	
		<div id="tbContentCell" style="width: 60px; padding-left: 0px; padding-top: 6px; height: 24px;">
			<input type="checkbox" id="{$hash}" class="torrent" style="margin-bottom: 5px;" />{if $web->getState($hash) eq 0}<div style="cursor: pointer; display: inline;"><img src="{$DIR_IMG}bullet_go.png" class="start" id="{$hash}" alt="Start" /></div>&nbsp;&nbsp;{else}<div style="cursor: pointer; display: inline;"><img src="{$DIR_IMG}cross.png"  class="stop" id="{$hash}" alt="Stop" /></div>&nbsp;&nbsp;{/if}<div style="cursor: pointer; display: inline;"><img src="{$DIR_IMG}delete.png" class="erase" id="{$hash}" alt="Delete" /></div>
		</div>
		<div id="tbContentCell" style="width: 380px; text-align: left; padding-left: 10px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{if $web->getState($hash) eq 1 && $web->getPercent($hash) neq 100}
				{assign var="color" value="green"}
			{/if}
			{if $web->getState($hash) eq 1 && $web->getPercent($hash) eq 100}
				{assign var="color" value="blue"}
			{/if}
			{if $web->getState($hash) eq 0}
				{assign var="color" value="black"}
			{/if}
			<span style="color: {$color};">{$web->getName($hash)|truncate:76:". . ."}{if $web->isHashChecking($hash) eq true} [CHECKING HASH]{/if}</span>
		</div>
		<div id="tbContentCell" style="width: 55px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getTotalSeeds($hash)} ({$web->getConnSeeds($hash)})
		</div>
		<div id="tbContentCell" style="width: 55px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getTotalPeers($hash)} ({$web->getConnPeers($hash)})
		</div>
		<div id="tbContentCell" style="width: 45px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getDownRate($hash)}
		</div>
		<div id="tbContentCell" style="width: 45px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getUpRate($hash)}
		</div>
		<div id="tbContentCell" style="width: 45px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getDone($hash)}
		</div>
		<div id="tbContentCell" style="width: 55px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getSize($hash)}
		</div>
		<div id="tbContentCell" style="width: 45px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getPercent($hash)}%
		</div>
			{if $web->getRatio($hash) < 1}
				{assign var=ratio value="red"}
			{else}
				{assign var=ratio value="green"}
			{/if}
		<div id="tbContentCell" style="color: {$ratio}; width: 35px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getRatio($hash)}
		</div>
		<div id="tbContentCell" style="width: 60px;" onclick="servOC({$clau},'{$hash}','#cad9ea');">
			{$web->getETA($hash)}
		</div>
	</div>
</div>
<div class="tbBulk" id="ihtr{$clau}" style="display: none; height: auto;">
	<div id="tbColTab">{include file="tabsL.tpl.php" id=$clau hash=$hash}</div>
			<div id="tab{$hash}" style="border: 1px solid #d4d4d4; border-top-width: 0px; width: 891px; float: left; display: block;"></div>
</div>