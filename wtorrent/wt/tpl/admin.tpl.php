<form method="post" action="">

	{foreach key=clau item=user from=$web->showUsers() name="listU"}
	{if $smarty.foreach.listU.first}
	<table id="user_table">
		<tr class="th_header">
			<th>
				
			</th>
			<th class="th_user">
				{$str.user}
			</th>
			<th class="th_dir">
				{$str.default_dir}
			</th>
			<th class="th_default">
				{$str.force_default}
			</th>
			<th class="th_admin">
				{$str.admin}
			</th>
		</tr>
	{/if}
		<tr class="user_table_row">
			<td class="tb_ckbox">
				<input name="users[{$user.id}]" id="users_{$user.id}" type="checkbox" />
			</td>
			<td class="tb_user">
				<label for="users_{$user.id}">
					{$user.user}
				</label>
			</td>
			<td class="tb_dir">
				{$user.dir}
			</td>
			<td class="tb_force">
				{if $user.force_dir eq 1}{$str.yes}{else}{$str.no}{/if}
			</td>
			<td class="tb_admin">
				{if $user.admin eq 1}{$str.yes}{else}{$str.no}{/if}
			</td>
		</tr>
	{if $smarty.foreach.listU.last}
	</table>
<p><input type="hidden" name="delete" /></p>
<div class="delete_sign">
	<img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
	<input type="image" src="{$DIR_IMG}cross.png" title="{$str.erase}" value="delete selected" onclick="javascript:return confirm('{$str.conf_user_del}')" />
</div>
{/if}
{foreachelse}
<div class="no_users">{$str.no_users}</div>
{/foreach}
</form>
<br />
<form method="post" action="{$web->getURL()}">
	{include file="common/title.tpl.php" text=$str.add_user}
	<div class="user_form">
		{include file="common/forms/input_text.tpl.php" text=$str.user name="user" size='20'}
		{include file="common/forms/input_text.tpl.php" text=$str.password name="passwd" size='20'}
		{include file="common/forms/input_checkbox.tpl.php" text=$str.admin name="admin"}
		{include file="common/forms/input_text.tpl.php" text=$str.default_dir name="default_dir" size='40'}
		{include file="common/forms/input_checkbox.tpl.php" text=$str.force_default name="force_dir"}
		{include file="common/forms/input_button.tpl.php" text=$str.add_user name="adduser"}
	</div>
</form>
{include file="common/title.tpl.php" text=$str.ch_bandwidth}
<div class="speeds">
	<form method="post" action=""><span class="sp_rates"><b>{$str.down_limit}</b> <input type="text" size="2" name="down_rate" value="{$web->getDownLimit()}" /> {$str.kb_units} <input type="submit" value="{$str.change_dlimit}" name="ch_dw" /></span></form>
	<form method="post" action=""><span class="sp_rates"><b>{$str.up_limit}</b> <input type="text" size="2" name="up_rate" value="{$web->getUpLimit()}" /> {$str.kb_units} <input type="submit" value="{$str.change_ulimit}" name="ch_up" /></span></form>
</div>