<input type="checkbox" class="torrentCheckbox" style="margin: 0 0 5px 3px;" />
{if $web->getState($hash) eq 0 || $web->getOpen($hash) eq 0}
	<img src="{$DIR_IMG}bullet_go.png" class="start" alt="{$str.start}" title="{$str.start}" />
{else}
	<img src="{$DIR_IMG}cross.png"  class="stop" alt="{$str.stop}" title="{$str.stop}" />
{/if}
{if $web->getOpen($hash) neq 0}
	<img src="{$DIR_IMG}lock_delete.png" class="close" alt="{$str.close}" title="{$str.close}" />
{/if}
	<img src="{$DIR_IMG}delete.png" class="erase" alt="{$str.erase}" title="{$str.erase}" />
{if $web->isHashChecking($hash) neq true}
	<img src="{$DIR_IMG}c_hash.png" class="chash" alt="{$str.chash}" title="{$str.chash}" />
{/if}