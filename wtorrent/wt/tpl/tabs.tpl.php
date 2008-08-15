<ul id="tabs">
	{if $web->getTplName() eq listT} 
	<li>
		<div class="tabs" id="public">
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
		<div class="tabs" id="private">
			<div class="text">
				{$str.pr_torrents}
			</div>
			<div class="rtorrent_left">
				
			</div>
		</div>
	</li>
	{foreach item=view from=$web->getViews() name="views"}
	<li>
		<div class="tabs" id="{$view}">
			<div class="rtorrent_text">
				{if $str.$view}
				  {$str.$view}
				{else}
				  {$view}
				{/if}
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
	{/if}
</ul>