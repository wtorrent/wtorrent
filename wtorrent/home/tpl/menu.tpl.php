<div id="menu_l">
	
</div>
<div id="menu_c">
	{assign var="menu_items" value=$web->getMenu()}
	{assign var="width" value=$web->getWidth($width_total, $menu_items)}
	{foreach item="cls" key="name" from=$menu_items name="menu"}
		<a style="width: {$width}px;" href="{$SRC_INDEX}?cls={$cls}"><img src="{$DIR_IMG}menu/{$cls}.png" />{$name}</a>
		{if $smarty.foreach.menu.last}
		<a style="width: {math equation="x + 1" x=$width}px;" href="{$SRC_INDEX}?logout"><img src="{$DIR_IMG}menu/disconnect.png" />{$str.logout}</a>
		{/if}
	{/foreach}
</div>
<div id="menu_r">
	
</div>