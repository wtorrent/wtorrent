<ul id="tabs">
	{if $web->getTplName() eq listT} 
	<li>
		<div class="tabs" onclick="load('content', 'public');">
			<div class="left">
				
			</div>
			<div class="text">
				{$str.pl_torrents}
			</div>
			<div class="middle">
				
			</div>
		</div>
	</li>
	<li>
		<div class="tabs" onclick="load('content', 'private');">
			<div class="text">
				{$str.pr_torrents}
			</div>
			<div class="rtorrent_left">
				
			</div>
		</div>
	</li>
	{/if}
	{foreach item=view from=$web->getViews() name="views"}
	<li>
		<div class="tabs" onclick="load('content', '{$view}');">
			<div class="rtorrent_text">
				{$view}
			</div>
			{if $smarty.foreach.views.last}
				<div class="rtorrent_right">
					
				</div>
			{else}
				<div class="rtorrent_middle">
					
				</div>
			{/if}
			</div>
	</li>
	{/foreach}
</ul>