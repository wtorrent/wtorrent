<?php require_once('conf/sample.user.conf.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title><?=TITLE?></title>
  <link rel="stylesheet" type="text/css" href="<?=DIR_CSS_ESTIL?>" media="all" />
  <style type="text/css" media="screen">
  	.input_row{
		height: 20px;
		width: 100%;
	}
	.input_row > div {
		float: left;
	}
	.input_cont{
		height: 20px;
		padding-top: 6px;
		font-size:12px;
		width: 80px;
		text-align: left;
	}
  </style>
</head>

<body>
<div style="font-family: georgia; font-size: 20px; text-align: center; font-weight: bold; padding-top: 8px; margin: 0px auto; background-color: #ffffff; width: 300px; height: 30px; top: 0px; border-bottom: 1px solid #d4d4d4; border-left: 1px solid #d4d4d4; border-right: 1px solid #d4d4d4;">
	Install
</div>
<?php
function bool2string($val){
	if($val){ return "true"; } return "false";
}
function check_required_fields($_REQUEST){
	$retv = FALSE;
	$retv = $retv || strlen($_REQUEST['urlbasef']) == 0;
	$retv = $retv || strlen($_REQUEST['webauthf']) == 0;
	$retv = $retv || strlen($_REQUEST['authdirf']) == 0;
	$retv = $retv || strlen($_REQUEST['portnumf']) == 0;
	$retv = $retv || strlen($_REQUEST['hostf']   ) == 0;
	$retv = $retv || strlen($_REQUEST['userf']   ) == 0;
	$retv = $retv || strlen($_REQUEST['passwdf'] ) == 0;
	$retv = $retv || strlen($_REQUEST['confirmf']) == 0;
	$retv = $retv || strlen($_REQUEST['torrdirf']) == 0;
	$retv = $retv || strlen($_REQUEST['datadirf']) == 0;
	$retv = $retv || strlen($_REQUEST['appdirf'] ) == 0;
	$retv = $retv || strcmp($_REQUEST['passwdf'], $_REQUEST['confirmf']) != 0;
	return !$retv;
}

$conf_file = 'conf/user.conf.php';
// if(file_exists(DB_FILE))
// {
// 	echo '<div id="principal" class="principal" style="width: 500px;"><div style="font-size: 11px; margin-bottom: 12px; font-weight: bold;">';
// 	echo 'wTorrent has already been configured.<br /> If you wish to re-run the install, please delete or move your db file.';
// 	echo '</div></div></body></html>';
// 	die();
// }
if(isset($_REQUEST['create']))
{
	echo '<div class="messages" style="display: block; margin-bottom: 10px;">';
	if(!check_required_fields($_REQUEST)){
		echo "<p><b>Please, Fill in the form</b></p>";
		echo "<table>";
		echo "<tr>";
		echo "<td>urlbasef" . "</td><td>---></td><td>" . $_REQUEST["urlbasef"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>webauthf" . "</td><td>---></td><td>" . $_REQUEST["webauthf"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>authdirf" . "</td><td>---></td><td>" . $_REQUEST["authdirf"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>portnumf" . "</td><td>---></td><td>" . $_REQUEST["portnumf"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>hostf"    . "</td><td>---></td><td>" . $_REQUEST["hostf"]    . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>userf"    . "</td><td>---></td><td>" . $_REQUEST["userf"]    . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>passwdf"  . "</td><td>---></td><td>" . $_REQUEST["passwdf"]  . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>confirmf" . "</td><td>---></td><td>" . $_REQUEST["confirmf"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>torrdirf" . "</td><td>---></td><td>" . $_REQUEST["torrdirf"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>datadirf" . "</td><td>---></td><td>" . $_REQUEST["datadirf"] . "</td>";
		echo "</tr><tr>";                                                 
		echo "<td>appdirf"  . "</td><td>---></td><td>" . $_REQUEST["appdirf"]  . "</td>";
		echo "</tr>";
		echo "</table></div>";
		die();
	}else{
		if(!file_exists($conf_file)){
			// Check password
			if($_REQUEST["passwdf"] != $_REQUEST["confirmf"]){
				echo "Password and confirmation do not match.</div>";
				die();
			}
			if(!($fd = fopen($conf_file, 'w'))){
				echo "There was an error with the permissions.<br />Run as root in a shell:</br><p style='font-family: Courier, \"Courier New\"'>chown -R [unixUser].[unixUser] [wTorrentFolder]; chmod -R a+w [wTorrentFolder]</p></div>";
				die();
			}
			$data = "<?php\n/*\nThis file is part of wTorrent.\n\nwTorrent is free software; you can redistribute it and/or modify\nit under the terms of the GNU General Public License as published by\nthe Free Software Foundation; either version 3 of the License, or\n(at your option) any later version.\n\nwTorrent is distributed in the hope that it will be useful,\nbut WITHOUT ANY WARRANTY; without even the implied warranty of\nMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\nGNU General Public License for more details.\n\nYou should have received a copy of the GNU General Public License\nalong with this program.  If not, see <http://www.gnu.org/licenses/>.\n\nModified version of class done by David Marco Martinez\n*/
			\n\n// Base URL of application (where index.php is) \ndefine( 'URL',				'" . $_REQUEST['urlbasef'] . "' );\ndefine( 'BASE',				'" . $_REQUEST['urlbasef'] . "' );\n\n// SQLite database file (set folder permision 0777 or chmod to httpd server) (wTorrent will create database when running install.php)\ndefine( 'DB_FILE',			'" . $_REQUEST['databasef'] . "');\n\n// Host or IP to connect to rTorrent\ndefine( 'RT_HOST',			'" . $_REQUEST['hostf'] . "');\ndefine( 'RT_PORT',			" . $_REQUEST['portnumf'] . ");\ndefine( 'RT_DIR',			'" . $_REQUEST['authdirf'] . "');\ndefine( 'RT_AUTH',			" . bool2string($_REQUEST['webauthf'] == 1) . ");\ndefine( 'RT_USER',			'" . $_REQUEST['userf'] . "');\ndefine( 'RT_PASSWD',		'" . $_REQUEST['passwdf'] . "');\n// where to use multicall or not\n// if wTorrent makes your rtorrent crash, set this to true\ndefine( 'NO_MULTICALL',		true);\n\n// Directory in which to save uploaded .torrent files (set folder permision 0777 or chmod to httpd server)\ndefine( 'DIR_TORRENTS',		'" . $_REQUEST['torrdirf'] . "');\n\n// Full path to application directory (where index.php is)\ndefine( 'DIR_EXEC',			'" . $_REQUEST['appdirf'] . "');\n\n// Permision to set for uploaded .torrent files (don't touch unless you know)\n\n// Default location to save downloaded files (can be set for every uploaded .torrent on the ui) \ndefine( 'DIR_DOWNLOAD',		'" . $_REQUEST['datadirf'] . "');\n\n";
			fwrite($fd, $data);
			// require_once($conf_file);
			fclose($fd);
			echo "Configuration file created!<p>Make any manual changes to $conf_file and click finish.</p></div>";
			echo	"<form action=\"install.php\" method=\"POST\">";
			echo		"<input type=\"hidden\" value=\"" . $_REQUEST['userf'] . "\" name=\"userf\" />";
			echo		"<input type=\"hidden\" value=\"" . $_REQUEST['passwdf'] . "\" name=\"passwdf\" />";
			echo		"<div style=\"width: 400px; margin: 0 auto 0 auto;\"><input type=\"submit\" value=\"Finish\" name=\"create\" /></div>";
			echo	"</form>";
		    
					
			die();
		}else{
			echo "Configuration file was already present!";
			echo "<p>Make any manual changes to $conf_file and click finish.</p></div>";
			echo	"<form action=\"install.php\" method=\"POST\">";
			echo		"<input type=\"hidden\" value=\"" . RT_USER . "\" name=\"userf\" />";
			echo		"<input type=\"hidden\" value=\"" . RT_PASSWD . "\" name=\"passwdf\" />";
			echo		"<div style=\"width: 400px; margin: 0 auto 0 auto;\"><input type=\"submit\" value=\"Finish\" name=\"create\" /></div>";
			echo	"</form>";
			die();
		}
		// if($_REQUEST['userf'] != '' && $_REQUEST['passwdf'] != '')
		// {
		// 	$db = new SQLiteDatabase(DB_FILE);
		// 	if(is_object($db))
		// 	{
		// 		$sql_create = "CREATE TABLE tor_passwd(id integer primary key, user text, passwd text, admin integer, dir text, force_dir integer);";
		// 		$sql_insert = "INSERT INTO tor_passwd VALUES(1,'" . $_REQUEST['userf'] . "','" . md5($_REQUEST['passwdf']) . "',1, '', 0);";
		// 		$sql_create_torrents = "create table torrents(hash string, user int, private int);";
		// 		$sql_create_feeds =  "CREATE TABLE feeds(id integer primary key, url text, user integer);";
		// 		$sql_create_cookies = "CREATE TABLE cookie(id integer primary key, userid integer, value text, hostname text);";
		// 		$res1 = $db->query($sql_create);
		// 		$res2 = $db->query($sql_insert);
		// 		$res3 = $db->query($sql_create_torrents);
		// 		$res4 = $db->query($sql_create_feeds);
		// 		$res5 = $db->query($sql_create_cookies);
		// 		if($res1 !== false && $res2 !== false)
		// 			echo 'Database succesfully created, please delete install.php and enjoy wTorrent';
		// 		else
		// 			echo 'Error during creation/insertion of user into the database, please check that the dir has correct permisions';
		// 		
		// 	} else {
		// 		echo 'Error trying to access database, plase check that you have compiled php with sqlite support';
		// 	}
		// } else {
		// 	echo 'Error, you must fill the form';
		// }
	}
	echo '</div>';
}
// else if(isset($_REQUEST['update']))
// {
// 	echo '<div class="messages">';
// 	$db = new SQLiteDatabase(DB_FILE);
//         if(is_object($db))
//         {
//         	$sql_create_torrents = "CREATE TABLE feeds(id integer primary key, url text, user integer);";
//                 $res = $db->query($sql_create_torrents);
//                 if($res !== false)
//                 	echo 'Update complete, please delete install.php';
//                 else
//                 	echo 'Error performing update, please check wTorrent for correct installation and/or reinstall';
//        	} else
//         	echo 'Could not connect to database, please check configuration';
// 	echo '</div>';
// }
?>
<div id="principal" class="principal" style="width: 500px;">
    <div id="contingut" class="contingut" style="width:450px; padding: 20px; padding-bottom: 0px; margin-top: 5px;">
    <div style="font-size: 11px; margin-bottom: 12px; font-weight: bold;">
    	Welcome to wTorrent!<br />
    	Please input the information required
    </div><hr noshade /><br />
	<form action="install2.php" method="POST">	
	<div class="input_row">
		<div class="input_cont">
			<b>Base URL:</b>
		</div>
		<div>
			<input type="text" size="40" value="<?php echo URL ?>" name="urlbasef" />
		</div>
	</div><br /><hr noshade /><br />
	<div class="input_row">
		<div class="input_cont">
			<b>Server Auth:</b>
		</div>
		<div>
			<input type="checkbox" value="<?php if(RT_AUTH){echo '1 checked';}else{echo '0';} ?>" checked name="webauthf" />
		</div>
		<div class="input_cont" style="padding-left: 5px;"><b>DIR:</b></div><div><input type="text" size="10" value="<?php echo RT_DIR ?>" name="authdirf" /></div>
	</div><br />
	<div class="input_row"><div class="input_cont"><b>Port:</b></div><div><input style="text-align: right;" type="text" size="5" value="<?php echo RT_PORT ?>" name="portnumf" /></div></div><br />
	<div class="input_row"><div class="input_cont"><b>Host:</b></div><div><input type="text" value="<?php echo RT_HOST ?>" name="hostf" /></div></div><br /><hr noshade /><br />
	<div class="input_row"><div class="input_cont"><b>User:</b></div><div><input type="text" value="<?php echo RT_USER ?>" name="userf" /></div></div><br />
	<div class="input_row"><div class="input_cont"><b>Password:</b></div><div> <input type="password" value="<?php echo RT_PASSWD ?>" name="passwdf" /></div></div><br />
	<div class="input_row"><div class="input_cont"><b>Confirm:</b></div><div> <input type="password" name="confirmf" /></div></div><br /><hr noshade /><br />
	<div class="input_row"><div class="input_cont"><b>Torrent dir:</b></div><div>  <input type="text" size="40" value="<?php echo DIR_TORRENTS ?>" name="torrdirf" /></div></div><br />
	<div class="input_row"><div class="input_cont"><b>Data dir:</b></div><div>     <input type="text" size="40" value="<?php echo DIR_DOWNLOAD ?>" name="datadirf" /></div></div><br />
	<div class="input_row"><div class="input_cont"><b>wTorrent dir:</b></div><div> <input type="text" size="40" value="<?php echo DIR_EXEC ?>" name="appdirf" /></div></div><br />
	<div class="input_row">
		<div class="input_cont">
			<b>DB file:</b>
		</div>
		<div>
			<input type="text" value="<?php echo DB_FILE ?>" name="databasef" />
		</div>
	</div><br /><br />
	
	<div style="padding-bottom: 10px;"><input type="submit" value="Create" name="create" /></div>
	<!-- <div style="padding-bottom: 10px;"><input type="submit" value="Update (only 20071014 and previous versions users)" name="update" /></div> -->
	</form>
    </div>
</div>
</body>

</html>