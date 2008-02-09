<div style="cursor: pointer; height: 23px; text-align: center; font-family: arial; font-size: 10px; top: -1px; border-bottom: 1px solid #d4d4d4;">
{assign var="menu_items" value=$web->getDetailsMenu()}
{assign var="width" value=$web->getWidth($width_total, $menu_items)}
{foreach item="cls" key="name" from=$menu_items name="menu"}
	{if $smarty.foreach.menu.last}
	<a onclick="setIframe({$clau},'{$SRC_INDEX}?cls={$cls}&hash={$hash}');"><div class="buttonD" style="width: {math equation="x + 1" x=$width}px;">{$name}</div></a>
	{else}
	<a onclick="setIframe({$clau},'{$SRC_INDEX}?cls={$cls}&hash={$hash}');"><div class="buttonD" style="border-right: 1px solid #d4d4d4; width: {$width}px;">{$name}</div></a>
	{/if}
{/foreach}
</div>