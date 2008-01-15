<div id="tabsL{$id}" class="tabsLeft">
<ul id="tabsL">
{if $web->getTplName() eq listT} 
    	<li><div id="itab{$hash}" class="tabsL"><img src="{$DIR_IMG}information.png" /></div></li>
    	<li><div id="ftab{$hash}" class="tabsL"><img src="{$DIR_IMG}folder_explore.png" /></div></li>
    	<li><div id="ttab{$hash}" class="tabsL"><img src="{$DIR_IMG}chart_organisation.png" /></div></li>
{/if}
</ul>
</div>