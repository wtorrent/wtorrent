<table style="border-collapse: collapse; width: 100%; margin-left: auto; margin-right: auto;">
	<tr style="background-color: #F1F3F5; border-bottom: 1px solid #d4d4d4; font-family: arial; font-size: 11px; font-weight: bold;">
		<td style="padding: 5px;">{$str.ip}</td>
		<td style="width: 80px; padding: 5px; text-align: center;">{$str.up_peer}</td>
		<td style="width: 80px; padding: 5px; text-align: center;">{$str.down_peer}</td>
		<td style="width: 100px; padding: 5px; text-align: center;">{$str.connection}</td>
		<td style="width: 50px; padding: 5px; text-align: center;">{$str.percent_done}</td>
		<td style="width: 50px; padding: 5px; text-align: center;">{$str.encryption}</td>
		<td style="width: 60px; padding: 5px; text-align: center;">{$str.down}</td>
		<td style="width: 150px; padding: 5px; text-align: center;">{$str.client}</td>
	</tr>
{foreach key=clau item=peer from=$web->getPeers()}
	<tr style="font-family: arial; font-size: 11px;">
		<td style="font-weight: bold; padding: 4px 4px 4px 20px; text-align: left">
			{$peer.ip}
		</td>
		<td style="padding: 4px;">
			{$peer.up_peer}
		</td>
		<td style="padding: 4px;">
			{$peer.down_peer}
		</td>
		<td style="padding: 4px;">
			{if $peer.incoming eq 1}
				{$str.incoming}
			{else}
				{$str.outgoing}
			{/if}
		</td>
		<td style="padding: 4px;">
			{$peer.done}%
		</td>
		<td style="padding: 4px;">
			{if $peer.encrypted eq 1}
				{$str.yes}
			{else}
				{$str.no}
			{/if}
		</td>
		<td style="padding: 4px;">
			{$peer.down}
		</td>
		<td style="padding: 4px;">
			{$peer.client}
		</td>
	</tr>
{foreachelse}
</table>
<div class="noPeers">
	{$str.no_peers}
</div>
{/foreach}