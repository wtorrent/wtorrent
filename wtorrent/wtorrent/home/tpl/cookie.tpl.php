{*<div style="width: 101%; text-align: right;">
<form method="POST" action="{$SRC_INDEX}?cls={$web->getCls()}">
<input type="submit" name="up_pr" />
</form>
</div>*}

		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.tl_cookies}</div>
		{foreach from=$web->getCookies() item=cookie name=cookies}
		{if $smarty.foreach.cookies.first}
		<table style="border: 1px solid #d4d4d4; border-collapse: collapse; width: 850px; margin: 20px auto; margin-top: 10px;">
			<tr style="background-color: #d7e4ef; font-size: 11px; font-weight: bold; border-bottom: 1px solid #d4d4d4;">
				<td style="padding: 5px;">{$str.cookies_hostname}</td>
				<td style="padding: 5px;">{$str.cookies_value}</td>
				<td style="padding: 5px;">&nbsp;</td>
			</tr>
			{/if}
			<tr>
				<td style="padding: 5px;">{$cookie.hostname}</td>
				<td style="padding: 5px;">{$cookie.value}</td>
				<td style="padding: 7px;"><a href="{$SRC_INDEX}?cls={$web->getCls()}&erase={$cookie.id}" title="{$str.cookie_erase}"><img src="{$DIR_IMG}feed_delete.png" alt="{$str.cookie_erase}" /></a></td>
			{if $smarty.foreach.cookies.last}
			</tr>
		</table>
		{/if}
		{foreachelse}
        	<div style="height: 30px; width: 100%; text-align: center; font-style: italic; font-size: 12px;">{$str.cookie_no_cookies}</div>
        {/foreach}
		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.tl_cookie_add}</div>
		<div style="text-align: left; padding-left: 30px; padding-bottom: 15px; padding-top: 5px; font-size: 14px;"><form method="POST" action="{$SRC_INDEX}?cls={$web->getCls()}"> {$str.cookie_host}: <input type="text" name="cookie_host" size="30" />&nbsp;&nbsp;{$str.cookie_value}: <input type="text" name="cookie_value" size="30" />&nbsp;&nbsp;<input type="submit" name="add_cookie" value="{$str.cookie_add}" /></form></form></div>

