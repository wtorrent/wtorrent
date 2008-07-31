<div id="tabsL{$id}" class="tabsLeft">
<ul class="tabsL">
{if $web->getTplName() eq listT} 
    	<li id="Itab{$hash}" class="tabsL info" onclick="load('tab{$hash}', 'info');"></li>
    	<li id="Ftab{$hash}" class="tabsL files" onclick="load('tab{$hash}', 'files');"></li>
    	<li id="Ttab{$hash}" class="tabsL trackers" onclick="load('tab{$hash}', 'trackers');"></li>
	<li id="Ptab{$hash}" class="tabsL peers" onclick="load('tab{$hash}', 'peers');"></li>
{/if}
</ul>
</div>