<div id="tabsL{$id}" class="tabsLeft">
<ul id="tabsL">
{if $web->getTplName() eq listT} 
    	<li><div id="itab{$hash}" class="tabsL"><img src="{$DIR_IMG}information.png" onclick="load('tab{$hash}', 'info');" /></div></li>
    	<li><div id="ftab{$hash}" class="tabsL"><img src="{$DIR_IMG}folder_explore.png" onclick="load('tab{$hash}', 'files');" /></div></li>
    	<li><div id="ttab{$hash}" class="tabsL"><img src="{$DIR_IMG}chart_organisation.png"  onclick="load('tab{$hash}', 'trackers');"/></div></li>
{/if}
</ul>
</div>