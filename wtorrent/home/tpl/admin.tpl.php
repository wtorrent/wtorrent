	<form method="POST" action="">
	
        {foreach key=clau item=user from=$web->showUsers() name="listU"}
        {if $smarty.foreach.listU.first}
        	<div style="width: 650px; height: 24px; margin: 0px auto; margin-bottom: 0px; background-color: #d7e4ef; border: 1px solid #d4d4d4; font-size: 11px;">
        		<div style="text-align: left; padding: 5px; padding-left: 30px; font-weight: bold; width: 310px; float: left;"><b>{$str.user}</b></div>
        		<div style="text-align: center; padding: 5px; font-weight: bold; width: 290px; float: left;"><b>{$str.admin}</b></div>
        	</div>
        {/if}
            <div style="width: 650px; height: 24px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 0px 1px; font-size: 11px;">
        		<div style="tex-talign: center; padding: 3px 0px 0px 0px; width: 30px; float: left;"><input type="checkbox" name="users[{$user.id}]" /></div>
				<div style="text-align: left; padding: 5px; width: 310px; float: left;">{$user.user}</div>
        		<div style="text-align: center; padding: 5px; width: 290px; float: left;">{$user.admin}</div>
        	</div>
		{if $smarty.foreach.listU.last}
			<div style="width: 650px; height: 2px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 1px 1px;">
        	</div>
        	<div style="height: 20px; text-align: left; margin-left: 155px; margin-top: 3px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" /><input type="image" src="{$DIR_IMG}cross.png"value="delete selected" name="delete" onClick="javascript:return confirm('{$str.conf_user_del}')" /></div>
        {/if}
        {foreachelse}
        	<div style="font-size: 12px; font-style: italic;">{$str.no_users}</div>
		{/foreach}
	</form>
	<br />
	<form method="POST" action="">
		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: center; margin-left: 30px; margin-right: 30px; padding-bottom: 5px;margin-bottom: 10px;">{$str.add_user}</div>
		<div style="width: 100%; height: 25px; float: none; display: block;">
			<div style="float: left; width: 60px; margin-left: 20px;font-size: 11px; padding-top: 3px; text-align: center;"><b>{$str.user}: </b></div><div style="float: left; width: 200px;"><input type="text" name="user" /></div>
			<div style="float: left; width: 80px; font-size: 11px; padding-top: 3px;"><b>{$str.password}: </b></div><div style="float: left; width: 200px;"><input type="text" name="passwd" /></div>
			<div style="float: left; width: 60px; font-size: 11px; padding-top: 3px;"><b>{$str.admin}: </b></div><div style="float: left; width: 30px;"><input type="checkbox"" name="admin" /></div>
			<div style="float: left; width: 100px; text-align: center; padding-top: 0px;"><input type="submit" value="Add user" name="adduser" /></div>
		</div>
	</form>
	<div style="margin-top: 10px; border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: center; margin-left: 30px; margin-right: 30px; padding-bottom: 5px;margin-bottom: 10px;">{$str.ch_bandwidth}</div>
	<div style="width: 100%; font-size: 12px; height: 40px; margin-top: 10px;">
        	<form method="POST" action=""><div style="width: 50%; float: left;"><b>{$str.down_limit}</b> <input type="text" size="2" name="down_rate" value="{$web->getDownLimit()}" /> {$str.kb_units} <input type="submit" value="{$str.change_dlimit}" name="ch_dw" /></div></form>
        	<form method="POST" action=""><div style="width: 50%; float: left;"><b>{$str.up_limit}</b> <input type="text" size="2" name="up_rate" value="{$web->getUpLimit()}" /> {$str.kb_units} <input type="submit" value="{$str.change_ulimit}" name="ch_up" /></div></form>
     </div>