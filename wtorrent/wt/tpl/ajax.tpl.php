{if $web->isRegistered()}
	{include file=$web->getTpl()}
{else}
	{$str.log_in}
{/if}
