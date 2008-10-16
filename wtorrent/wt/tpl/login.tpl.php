<div id="login_t">
	
</div>
<div id="login_m">
  <div id="login">
    <form id="form-login" action="{$web->getURL()}" method="post">
      <p id="login-username">
	<label for="userf">{$str.user}:</label>
	<input name="userf" id="userf" type="text" />
      </p>
      <p id="login-password">
	<label for="passwdf">{$str.password}:</label>
	<input type="password" id="passwdf" name="passwdf" />
      </p>
      <p id="login-input">
	<input type="submit" value="{$str.login}" name="user_login" id="user_login" />
      </p>
    </form>
  </div>
</div>
<div id="login_b">
	
</div>
