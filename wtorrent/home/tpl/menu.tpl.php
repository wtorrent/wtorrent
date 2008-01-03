<div style="height: 25px; text-align: center; font-family: arial; font-size: 12px;">
{assign var="menu_items" value=$web->getMenu()}
{assign var="width" value=$web->getWidth($width_total, $menu_items)}
{foreach item="cls" key="name" from=$menu_items name="menu"}
	{if $smarty.foreach.menu.last}
	<div class="button" style="width: {math equation="x + 1" x=$width}px;"><a href="{$SRC_INDEX}?cls={$cls}">{$name}</a></div>
	{else}
	<div class="button" style="border-right: 1px solid #d4d4d4; width: {$width}px;"><a href="{$SRC_INDEX}?cls={$cls}">{$name}</a></div>
	{/if}
{/foreach}
</div>