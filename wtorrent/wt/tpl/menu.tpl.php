<div id="menu_l">
	
</div>
<div id="menu_c">
	{assign var="menu_items" value=$web->getMenu()}
	{assign var="width" value=$web->getWidth($width_total, $menu_items)}
	{foreach item="cls" key="name" from=$menu_items name="menu"}
		<a style="width: {$width}px;" href="{$SRC_INDEX}?cls={$cls}"><img src="{$DIR_IMG}menu/{$cls}.png" alt="{$name}" />{$str.$cls}</a>
		{if $smarty.foreach.menu.last}
		<a style="width: {math equation="x + 1" x=$width}px;" href="{$SRC_INDEX}?logout"><img src="{$DIR_IMG}menu/disconnect.png" alt="{$str.logout}" />{$str.logout}</a>
		{/if}
	{/foreach}
	<div id="server_info">
		<div id="space">
			<div class="space_text">
				{$str.space} <span class="space_used_total">{$web->getUsedSpace()}/{$web->getTotalSpace()}</span>
			</div>
			<div class="prog_bar_cont">
				<div class="prog_bar" style="width: {$web->getUsedPercent()}%">

				</div>
			</div>
			<div class="space_text">
				{$str.free} <span class="space_free">{$web->getFreeSpace()}</span>
			</div>
		</div>
		<div id="speed">
			{$str.dw_rate} <span class="dw_rate">{$web->getDownload()}</span>
			{$str.up_rate} <span class="up_rate">{$web->getUpload()}</span>
		</div>
	</div>
</div>
<div id="menu_r">
	
</div>
