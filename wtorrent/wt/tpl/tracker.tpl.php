{*<div style="width: 100%; font-family: arial; font-size: 11px; padding: 8px 2px 8px 2px;">
	<b style="color: #4C8CC4;">Trackers:</b>
</div>*}
<form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}&tpl=details&hash={$web->getHash()}">
	<table style="border-collapse: collapse; width: 100%; margin-left: auto; margin-right: auto; border-bottom: 1px solid #d4d4d4;">
		<tr style="background-color: #F1F3F5; border-bottom: 1px solid #d4d4d4;">
			<td style="width: 20px;">&nbsp;</td>
			<td style="width: 16px;">&nbsp;</td>
			<td style="padding: 5px; font-family: arial; font-size: 11px; font-weight: bold;">{$str.url}</td>
			<td style="width: 35px; padding: 5px; font-family: arial; font-size: 11px; font-weight: bold; text-align: center;">{$str.tb_seeds}</td>
			<td style="width: 35px; padding: 5px; font-family: arial; font-size: 11px; font-weight: bold; text-align: center;">{$str.tb_peers}</td>
		</tr>	
		{foreach key=clau item=tracker from=$web->getTrackers()}
		<tr>
			<td style="text-align: center;"><input type="checkbox" class="trackers{$web->getHash()}" id="{$clau}" /></td>
			<td style="text-align: center; margin: auto;">
				{if $tracker.enabled eq 1}<img src="{$DIR_IMG}bullet_green.png" style="margin: auto; margin-top: 5px;" />{else}<img src="{$DIR_IMG}bullet_red.png" style="margin: auto;" />{/if}
			</td>
			<td style="font-family: arial; font-size: 11px; padding: 2px 5px 2px 10px; text-align: left;">
				{$tracker.url}
			</td>
			<td style="font-family: arial; font-size: 11px; text-align: center;">
				{$tracker.scrape_completed}
			</td>
			<td style="font-family: arial; font-size: 11px; text-align: center;">
				{$tracker.scrape_incomplete}
			</td>
		</tr>
		{/foreach}
	</table>
	<div class="trackersButtons" style="height: 20px; text-align: left; margin-left: 2px; margin-top: 3px; height: 40px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
		<select id="st{$web->getHash()}">
			<option value="1">{$str.enable}</option>
			<option value="0">{$str.disable}</option>
		</select>
		<div class="but_bottom trackersEnable"> {$str.change_tr} </div>
		<div class="but_bottom trackersCheckAll"> {$str.check_all} </div>
		<div class="but_bottom trackersUncheckAll"> {$str.uncheck_all} </div>
		<div class="but_bottom trackersInvertAll"> {$str.invert_all} </div>
	</div>
</form>
