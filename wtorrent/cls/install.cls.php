<?php
/*
This file is part of wTorrent.

wTorrent is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

wTorrent is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Created by Roger Pau Monné
*/
class install extends Web
{
	protected $client;
	protected $data;
	protected $save;
	private static $options = array(
		'language' => 'string',
		'db_file' => 'string',
		'rt_host' => 'string',
		'rt_port' => 'integer',
		'rt_dir' => 'string',
		'rt_auth' => 'boolean',
		'rt_user' => 'string',
		'rt_passwd' => 'string',
		'no_multicall' => 'boolean',
		'effects' => 'boolean',
		'dir_torrents' => 'string',
		'dir_exec' => 'string',
		'dir_download' => 'string',
		'user' => 'string',
		'passwd' => 'string'
	);
	private $defaults = array(
		'language' => 'en',
		'db_file' => 'db/database.db',
		'rt_host' => 'localhost',
		'rt_port' => 80,
		'rt_dir' => '/RPC2',
		'rt_auth' => false,
		'rt_user' => '',
		'rt_passwd' => '',
		'no_multicall' => true,
		'effects' => true,
		'dir_torrents' => 'torrents/',
		'dir_exec' => '',
		'dir_download' => '/data',
		'user' => '',
		'passwd' => ''
		);
	protected $user_options;

	public function __construct( )
	{
		parent::__construct(true);
		$this->save = false;
		if(isset($this->_request))
		{
			if(array_key_exists('try', $this->_request))
			{
				$this->tryConfig($this->createOptionsArray());
			}
			if(array_key_exists('save', $this->_request))
			{
				if(!empty($this->_request['user']) && !empty($this->_request['passwd']))
				{
					$this->saveConfig($this->createOptionsArray());
				} else {
					$this->addMessage($this->_str['missing_user']);
				}
			}
		}
		$this->defaults['dir_exec'] = DIR_EXEC;
	}
	/* Create an array from static $options and request data */
	private function createOptionsArray()
	{
		foreach(self::$options as $option => $type)
		{
			if($type == 'boolean')
			{
				if($this->_request[$option] == 1)
				{
					$options[$option] = true;
				} else {
					$options[$option] = false;
				}
			} else {
				$options[$option] = $this->_request[$option];
				settype($options[$option], $type);
			}
		}
		$this->user_options = $options;
		return $options;
	}
	/* try the config before giving the option to save it */
	private function tryConfig($options)
	{
		if(!is_writable('conf/user.conf.php'))
		{
			$this->addMessage($this->str['config_install_err']);
		}
		if(!is_readable(DIR_LANG . $options['language'] . '.txt'))
		{
			$this->addMessage($this->_str['language_err']);
		}
		$path = explode('/', $options['db_file']);
		$num_folders = count($path) - 1;
		for($i=0;$i<$num_folders;$i++)
		{
			$folder .= $path[$i];
		}
		if(empty($folder))
		{
			$folder = '.';
		}
		if(!is_writable($folder))
		{
			$this->addMessage($this->_str['db_file_err']);
		}
		if(!$this->tryClient($options['rt_dir'], $options['rt_host'], $options['rt_port'], $options['rt_auth'], $options['rt_user'], $options['rt_passwd']))
		{
			$this->addMessage($this->_str['rt_install_err']);
		}
		if(!is_writable($options['dir_torrents']))
		{
			$this->addMessage($this->_str['dir_torrents_err']);
		}
		if(!file_exists($options['dir_exec']))
		{
			$this->addMessage($this->_str['dir_exec_err']);
		}
		if(!$this->getMessages())
		{
			$this->save = true;
			$this->addMessage($this->_str['try_ok']);
		}
	}
	private function saveConfig($options)
	{
		$this->save = true;
		$header = "<?php\n/* wTorrent autoconfiguration file. Created " . date('j/n/Y') . " */\n";
		$fh = fopen("conf/user.conf.php", "w");
		fwrite($fh, $header);
		foreach($options as $define => $option)
		{
			if($define != 'user' && $define != 'passwd')
			{
				$line = "define ('" . strtoupper($define) . "', ";
				if(is_bool($option) || is_numeric($option))
				{
					$temp = $option;
					if(is_bool($temp))
					{
						if($temp)
						{
							$temp = 'true';
						} else {
							$temp = 'false';
						}
					}
					$line .= $temp . ");";
				}
				if(is_string($option))
				{
					$line .= "'" . $option . "');";
				}
				$line .= "\n";
				fwrite($fh, $line);
			}
		}
		$footer = "?>\n";
		fwrite($fh, $footer);
		fclose($fh);
		if (!defined('DB_DSN') && defined('DB_FILE'))
		{
			define('DB_DSN', 'sqlite:' . DIR_EXEC . DB_FILE);
		}
		$db = new PDOe('sqlite:' . $options['db_file'], null, null, array(PDO::ERRMODE_EXCEPTION));
		if(is_object($db))
		{
			$db->modify('CREATE TABLE tor_passwd(id integer primary key, user text, passwd text, admin integer, dir text, force_dir integer)');
			$db->modify('INSERT INTO tor_passwd VALUES(1, ?, ?, 1, \'\', 0)', $options['user'], md5($options['passwd']));
			$db->modify('CREATE TABLE torrents(hash string, user int, private int)');
			$db->modify('CREATE TABLE feeds(id integer primary key, url text, user integer)');
			$db->modify('CREATE TABLE cookie(id integer primary key, userid integer, value text, hostname text)');
		} else {
			$this->addMessage($this->_str['db_create_err']);
			return;
		}
		$this->addMessage($this->_str['create_config_ok']);
	}
	/* Return contents of language dir */
	public function getLanguages()
	{
		$dh = @opendir(DIR_LANG) or die('Cannot open language path');
		while($file = readdir($dh))
		{
			$lang = explode('.', $file);
			if($lang[1] == 'txt')
			{
				$languages[] = $lang[0];
			}
		}
		return $languages;
	}
	public function getOption($option)
	{
		if(isset($this->user_options[$option]))
		{
			return $this->user_options[$option];
		} else {
			return $this->defaults[$option];
		}
	}
	/* xmlrpc basic testing functions */
	private function checkError($result)
	{
		if($result->errno == '0')
			return true;
		else
			return false;
	}
	private function tryClient($rt_dir, $rt_host, $rt_port, $rt_auth, $rt_user, $rt_passwd)
	{
		$this->client = new xmlrpc_client($rt_dir, $rt_host, $rt_port);

		if($rt_auth)
			$this->client->setCredentials($rt_user, $rt_passwd);
    
		$this->client->return_type = 'phpvals';
    $this->client->no_multicall = $no_multicall;
    	
    $message = new xmlrpcmsg("system.pid");
		$result = $this->client->send($message);

		return $this->checkError($result);
	}
	/* Basic defines for wTorrent to work that are in user.conf.php */
	final public static function setDefines()
	{
		define( 'DIR_EXEC',			str_replace( 'install.php' , '' , $_SERVER['SCRIPT_FILENAME'] ));
	}
	/* Void methods */
	public function construct()
	{
		
	}
	public function isRegistered()
	{
		return false;
	}
	protected function setPerm()
	{
		$this->admin = false;
	}
}
?>
