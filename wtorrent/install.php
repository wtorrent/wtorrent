fkdghdfñhgdfg<?php require_once('conf/home.conf.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-15" />
  <title><?=TITLE?></title>
  <link rel="stylesheet" type="text/css" href="<?=DIR_CSS_ESTIL?>" media="all" />
</head>

<body>
<div style="font-family: georgia; font-size: 20px; text-align: center; font-weight: bold; padding-top: 8px; margin: 0px auto; background-color: #ffffff; width: 300px; height: 30px; top: 0px; border-bottom: 1px solid #d4d4d4; border-left: 1px solid #d4d4d4; border-right: 1px solid #d4d4d4;">
	Install
</div>
<?php

if(isset($_REQUEST['create']))
{
	echo '<div class="messages">';
	if($_REQUEST['userf'] != '' && $_REQUEST['passwdf'] != '')
	{
		$db = new SQLiteDatabase(DB_FILE);
		if(is_object($db))
		{
			$sql_create = "CREATE TABLE tor_passwd(id integer primary key, user text, passwd text, admin integer);";
			$sql_insert = "INSERT INTO tor_passwd VALUES(1,'" . $_REQUEST['userf'] . "','" . md5($_REQUEST['passwdf']) . "',1);";
			$sql_create_torrents = "create table torrents(hash string, user int, private int);";
			$sql_create_feeds =  "CREATE TABLE feeds(id integer primary key, url text, user integer);";
			$res1 = $db->query($sql_create);
			$res2 = $db->query($sql_insert);
			$res3 = $db->query($sql_create_torrents);
			$res4 = $db->query($sql_create_feeds);
			if($res1 !== false && $res2 !== false)
				echo 'Database succesfully created, please delete install.php and enjoy wTorrent';
			else
				echo 'Error during creation/insertion of user into the database, please check that the dir has correct permisions';
				
		} else {
			echo 'Error trying to access database, plase check that you have compiled php with sqlite support';
		}
	} else {
		echo 'Error, you must fill the form';
	}
	echo '</div>';
}
if(isset($_REQUEST['update']))
{
	echo '<div class="messages">';
	$db = new SQLiteDatabase(DB_FILE);
        if(is_object($db))
        {
        	$sql_create_torrents = "CREATE TABLE feeds(id integer primary key, url text, user integer);";
                $res = $db->query($sql_create_torrents);
                if($res !== false)
                	echo 'Update complete, please delete install.php';
                else
                	echo 'Error performing update, please check wTorrent for correct installation and/or reinstall';
       	} else
        	echo 'Could not connect to database, please check configuration';
	echo '</div>';
}

?>
<div id="principal" class="principal" style="width: 500px;">
    <div id="contingut" class="contingut" style="width:450px; padding: 20px; padding-bottom: 0px; margin-top: 5px;">
    <div style="font-size: 11px; margin-bottom: 12px; font-weight: bold;">
    	Welcome to wTorrent!<br />
    	Please input your desired username/password
    </div>
	<form action="" method="POST">
	<div style="height: 20px; width: 100%;"><div style="height: 20px; padding-top: 6px; font-size:12px; width: 80px; float: left; text-align: left;"><b>User:</b></div><div style="float: left;"><input type="text" name="userf" /></div></div><br />
	<div style="height: 20px; width: 100%;"><div style="height: 20px; padding-top: 6px; font-size: 12px; width: 80px; float: left; text-align: left;"><b>Password:</b></div><div style="float: left;"> <input type="text" name="passwdf" /></div></div><br />
	<div style="padding-bottom: 10px;"><input type="submit" value="Create" name="create" /></div>
	<div style="padding-bottom: 10px;"><input type="submit" value="Update (only 20071014 and previous versions users)" name="update" /></div>
	</form>
    </div>
</div>
</body>

</html>
