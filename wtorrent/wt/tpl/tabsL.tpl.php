<div id="tabsL{$id}" class="tabsLeft">
	<ul class="tabsL">
		{if $web->getTplName() eq listT} 
			<li id="Itab{$hash}" class="tabsL info"></li>
			<li id="Ftab{$hash}" class="tabsL files"></li>
			<li id="Ttab{$hash}" class="tabsL trackers"></li>
			<li id="Ptab{$hash}" class="tabsL peers"></li>
		{/if}
	</ul>
</div>