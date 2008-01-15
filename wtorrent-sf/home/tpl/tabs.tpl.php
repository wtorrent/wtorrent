<ul id="tabs">
{if $web->getTplName() eq listT} 
    	<li><div id="public" class="tabs">{$str.pl_torrents}</div></li>
    	<li><div id="private" class="tabs">{$str.pr_torrents}</div></li>
{/if}
</ul>