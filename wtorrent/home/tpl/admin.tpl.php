	<form method="post" action="">
	
        {foreach key=clau item=user from=$web->showUsers() name="listU"}
        {if $smarty.foreach.listU.first}
	    <div id="user_table_header">
        	<div style="text-align: left; padding: 5px; padding-left: 30px; font-weight: bold; width: 200px; float: left;">{$str.user}</div>
                <div style="text-align: left; padding: 5px; font-weight: bold; width: 310px; float: left;">{$str.default_dir}</div>
                <div style="text-align: center; padding: 5px; font-weight: bold; width: 200px; float: left;">{$str.force_default}</div>
                <div style="text-align: center; padding: 5px; font-weight: bold; width: 40px; float: left;">{$str.admin}</div>
	    </div>
        {/if}
            <div class="user_table_row">
        	<div style="tex-talign: center; padding: 3px 0px 0px 0px; width: 30px; float: left;"><input name="users[{$user.id}]" type="checkbox" /></div>
		<div style="text-align: left; padding: 5px; width: 170px; float: left;">{$user.user}</div>
                <div style="text-align: left; padding: 5px; width: 310px; float: left;">{$user.dir}</div>
                <div style="text-align: center; padding: 5px; width: 220px; float: left;">{if $user.force_dir eq 1}{$str.yes}{else}{$str.no}{/if}</div>
        	<div style="text-align: center; padding: 5px; width: 40px; float: left;">{if $user.admin eq 1}{$str.yes}{else}{$str.no}{/if}</div>
	    </div>
		{if $smarty.foreach.listU.last}
			<div style="width: 815px; height: 2px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 1px 1px;" />
        	</div>
            <p><input type="hidden" name="delete" /></p>
        	<div style="height: 20px; text-align: left; margin-left: 85px; margin-top: 3px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" /><input type="image" src="{$DIR_IMG}cross.png" title="{$str.erase}" value="delete selected" onclick="javascript:return confirm('{$str.conf_user_del}')" /></div>
        {/if}
        {foreachelse}
        	<div style="font-size: 12px; font-style: italic;">{$str.no_users}</div>
		{/foreach}
	</form>
	<br />
	<form method="post" action="">
		{include file="common/title.tpl.php" text=$str.add_user}
		<div style="text-align: left; float: none; display: block; padding-left: 40px; margin-bottom: 10px;">
			{include file="common/forms/input_text.tpl.php" text=$str.user name="user" size='20'}
            {include file="common/forms/input_text.tpl.php" text=$str.password name="passwd" size='20'}
            {include file="common/forms/input_checkbox.tpl.php" text=$str.admin name="admin"}
            {include file="common/forms/input_text.tpl.php" text=$str.default_dir name="default_dir" size='40'}
            {include file="common/forms/input_checkbox.tpl.php" text=$str.force_default name="force_dir"}
            {include file="common/forms/input_button.tpl.php" text=$str.add_user name="adduser"}
		</div>
	</form>
	{include file="common/title.tpl.php" text=$str.ch_bandwidth}
	<div style="width: 100%; font-size: 12px; height: 40px; margin-top: 10px;">
        	<form method="post" action=""><span style="width: 50%; float: left;"><b>{$str.down_limit}</b> <input type="text" size="2" name="down_rate" value="{$web->getDownLimit()}" /> {$str.kb_units} <input type="submit" value="{$str.change_dlimit}" name="ch_dw" /></span></form>
        	<form method="post" action=""><span style="width: 50%; float: left;"><b>{$str.up_limit}</b> <input type="text" size="2" name="up_rate" value="{$web->getUpLimit()}" /> {$str.kb_units} <input type="submit" value="{$str.change_ulimit}" name="ch_up" /></span></form>
     </div>