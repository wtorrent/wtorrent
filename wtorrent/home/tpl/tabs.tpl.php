<ul id="tabs">
{if $web->getTplName() eq listT} 
    	<li><div class="tabs" onclick="load('content', 'public');">{$str.pl_torrents}</div></li>
    	<li><div class="tabs" onclick="load('content', 'private');">{$str.pr_torrents}</div></li>
{/if}
</ul>