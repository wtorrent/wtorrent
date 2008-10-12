<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>{$TITLE}</title>
	<link rel="stylesheet" href="{$DIR_CSS}reset.css" type="text/css" media="all" charset="utf8" />
	<link rel="stylesheet" href="{$DIR_CSS}estil.css" type="text/css" media="all" charset="utf8" />
	<link rel="shortcut icon" type="image/x-icon" href="{$DIR_IMG}favicon.ico" />
</head>

<body>
	{include file="messages.tpl.php"}
	<div id="main_t">
		
	</div>
	<div id="main_m">
		<div id="content">
			<h1>{$str.install}</h1>
			<form id="install" action="install.php" method="post" accept-charset="utf-8">
				<div id="wrapper" class="clearfix">
					<div id="col1">
						<h2>{$str.wtorrent_settings}</h2>
						<div class="row clearfix">
							<label for="language">{$str.language_info}</label> 
							<select name="language">
								{foreach from=$web->getLanguages() item=language}
									<option value="{$language}" {if $web->getOption('language') eq $language}selected="selected"{/if}>{$language}</option>
								{/foreach}
							</select>
						</div>
						<div class="row clearfix">
							<label for="db_file">{$str.db_file_info}</label> 
							<input type="text" name="db_file" value="{$web->getOption('db_file')}" id="db_file" />
						</div>
						<div class="row clearfix">
							<label for="rt_host">{$str.rt_host_info}</label> 
							<input type="text" name="rt_host" value="{$web->getOption('rt_host')}" id="rt_host" />
						</div>
						<div class="row clearfix">
							<label for="rt_port">{$str.rt_port_info}</label>
							<input type="text" name="rt_port" value="{$web->getOption('rt_port')}" id="rt_port" />
						</div>
						<div class="row clearfix">
							<label for="rt_dir">{$str.rt_dir_info}</label> 
							<input type="text" name="rt_dir" value="{$web->getOption('rt_dir')}" id="rt_dir" />
						</div>
						<div class="row clearfix">
							<label for="rt_auth">{$str.rt_auth_info}</label>
							<select name="rt_auth">
								<option value="1" {if $web->getOption('rt_auth')}selected="selected"{/if}>{$str.true}</option>
								<option value="0" {if !$web->getOption('rt_auth')}selected="selected"{/if}>{$str.false}</option>
							</select>
						</div>
						<div class="row clearfix">
							<label for="rt_user">{$str.rt_user_info}</label> 
							<input type="text" name="rt_user" value="{$web->getOption('rt_user')}" id="rt_user" />
						</div>
						<div class="row clearfix">
							<label for="rt_passwd">{$str.rt_passwd_info}</label> 
							<input type="text" name="rt_passwd" value="{$web->getOption('rt_passwd')}" id="rt_passwd" />
						</div>
						<div class="row clearfix">
							<label for="no_multicall">{$str.no_multicall_info}</label> 
							<select name="no_multicall">
								<option value="1" {if $web->getOption('no_multicall') eq 1}selected="selected"{/if}>{$str.true}</option>
								<option value="0" {if $web->getOption('no_multicall') eq 0}selected="selected"{/if}>{$str.false}</option>
							</select>
						</div>
						<div class="row clearfix">
							<label for="effects">{$str.effects_info}</label>
							<select name="effects">
								<option value="1" {if $web->getOption('effects')}selected="selected"{/if}>{$str.true}</option>
								<option value="0" {if !$web->getOption('effects')}selected="selected"{/if}>{$str.false}</option>
							</select>
						</div>
						<div class="row clearfix">
							<label for="dir_torrents">{$str.dir_torrents_info}</label> 
							<input type="text" name="dir_torrents" value="{$web->getOption('dir_torrents')}" id="dir_torrents" />
						</div>
						<div class="row clearfix">
							<label for="dir_exec">{$str.dir_exec_info}</label> 
							<input type="text" name="dir_exec" value="{$web->getOption('dir_exec')}" id="dir_exec" />
						</div>
						<div class="row clearfix">
							<label for="dir_download">{$str.dir_download_info}</label> 
							<input type="text" name="dir_download" value="{$web->getOption('dir_download')}" id="dir_download" />
						</div>
					</div>
					<div id="col2">
						<h2>{$str.user_password}</h2>
						<div class="row clearfix">
							<label for="user">{$str.user_info}</label> 
							<input type="text" name="user" value="{$web->getOption('user')}" id="user" />
						</div>
						<div class="row clearfix">
							<label for="passwd">{$str.passwd_info}</label> 
							<input type="text" name="passwd" value="{$web->getOption('passwd')}" id="passwd" />
						</div>
					</div>
				</div>
				<div class="row center clearfix">
					<input type="submit" name="try" value="{$str.try_info}" id="try" />
					<input type="submit" name="save" value="{$str.save_info}" id="save" />
				</div>
			</form>
		</div>
	</div>
	<div id="main_b">

	</div>
</body>
</html>