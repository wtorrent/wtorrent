<form method="post" action="">
	{include file="common/title.tpl.php" text=$str.ch_passwd}
	<div class="ch_passwd_form">
		{include file="common/forms/input_password.tpl.php" text=$str.old_passwd name="old_passwd" size='20'}
		{include file="common/forms/input_password.tpl.php" text=$str.new_passwd1 name="passwd1" size='20'}
		{include file="common/forms/input_password.tpl.php" text=$str.new_passwd2 name="passwd2" size='20'}
		{include file="common/forms/input_button.tpl.php" text=$str.ch_passwd name="ch_passwd"}
	</div>
</form>
{* <form method="post" action="{$web->getURL()}">
</form>*}
