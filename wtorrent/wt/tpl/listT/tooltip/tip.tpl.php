<div id="tipContent{$hash}" class="prototip {$web->getTstyle($hash)}" style="display: none;">
	{if !is_null($web->getTooltipText($hash))}
		<div class="toolbar">
	    	{$web->getTstate($hash)}
	    </div>
	    <div class="content">
	    	{$web->getTooltipText($hash)}
	    </div>
    {else}
	    <div class="content">
	    	{$web->getTstate($hash)}
	    </div>
    {/if}
</div>