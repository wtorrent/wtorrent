<div id="tabsL{$id}" class="tabsLeft">
<ul id="tabsL">
{if $web->getTplName() eq listT} 
    	<li>
				<div id="itab{$hash}" class="tabsL info" onclick="load('tab{$hash}', 'info');">
					 
				</div>
			</li>
    	<li>
				<div id="ftab{$hash}" class="tabsL files" onclick="load('tab{$hash}', 'files');">
					
				</div>
			</li>
    	<li>
				<div id="ttab{$hash}" class="tabsL trackers" onclick="load('tab{$hash}', 'trackers');">
					
				</div>
			</li>
{/if}
</ul>
</div>