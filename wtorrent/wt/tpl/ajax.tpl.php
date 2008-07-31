{if $web->registrado()}
	{include file=$web->getTpl()}
{else}
	{$str.log_in}
{/if}
