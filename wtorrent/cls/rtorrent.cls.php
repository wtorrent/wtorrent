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

Modified version of class done by David Marco Martinez
*/
class rtorrent extends Web
{
	protected $client;
	protected $multicall;
	protected $torrents;
	protected $data;
	
	private $menu = array('Main' => 'ListT',
						'Add Torrent' => 'AddT',
						'Feeds' => 'Feeds',
						'Cookie' => 'Cookie');
	private $menu_admin = array('Main' => 'ListT',
						'Add Torrent' => 'AddT',
						'Feeds' => 'Feeds',
						'Admin' => 'Admin',
						'Cookie' => 'Cookie');
	private $submenu	= array('General' => 'General&tpl=details',
						'Files' => 'Files&tpl=details',
						'Tracker' => 'Tracker&tpl=details');
	protected $admin;
    /////////////////////////////////// C O N S T R U C T O R A S  Y  D E S T R U C T O R A ///////////////////////////////////

	public function __construct( )
	{
		parent::__construct( );
		if(isset($this->_request['logout'])) $this->logout();
		if(isset($this->_request['user_login'])) $this->login($this->_request['userf'], $this->_request['passwdf']);
	}
	private function checkError($result)
	{
		if($result->errno == '0')
			return true;
		else
			return false;
	}
	protected function construct( ){}

	public function registrado( )
	{
			return !is_null( $this->_sesion->id_user );
	}
	public function compLogin($user, $passwd)
	{
		$passwd = md5($passwd);
		$sql = "select id from tor_passwd where user = '$user' and passwd = '$passwd'";
		$result = $this->_db->query( $sql );
		if(is_object($result))
		{
			$num = $result->numRows();

			if($num > 0)
				$return = $result->current();
			else
				$return = false;
		} else {
			$return = false;
		}
		return $return;
	}
	public function getUser( )
	{
		return $this->_sesion->user;
	}
	public function getIdUser( )
	{
		return $this->_sesion->id_user;
	}
	public function getMenu()
	{
		if($this->_sesion->admin === true)
			$return = $this->menu_admin;
		else 
			$return = $this->menu;
			
		return $return;
	}
	public function getDetailsMenu()
	{
		return $this->submenu;
	}
	public function getWidth($total, $menu_items)
	{
		return floor($total/(count($menu_items)+1)) - 1;
	}
	public function Admin($id)
	{
		$sql = "select admin from tor_passwd where id = '" . $this->_sesion->id_user . "'";
		$result = $this->_db->query( $sql );
		$admin = $result->current();
		if($admin['admin'] == 1)
			$return = true;
		else 
			$return = false;
			
		return $return;
	}
	public function login( $user, $password )
	{	
		$id = $this->compLogin($user, $password);
		if($id != false)
		{
			$this->_sesion->id_user = $id['id'];
			$this->_sesion->user = $user;
			$this->_sesion->admin = $this->Admin($id['id']);
			$return = true;
		} else {
			$return = false;
			$this->addMessage($this->_str['err_login']);
		}
		return $return;
	}
	public function isAdmin()
	{
		return $this->_sesion->admin;
	}
	public function logout( )
	{
		if($this->registrado())
		{
			$this->_sesion = null;
			$this->addMessage($this->_str['info_logout']);
		}
	}
	private function getPrivate()
	{
		$tt = array();
		$sql = "select hash, user from torrents";
		$result = $this->_db->query($sql);
		$torr = $result->fetchAll();
		foreach($torr as $torrent)
			$tt[$torrent['hash']] = $torrent['user'];
			
		return $tt;
	}
	public function setClient()
	{
		$this->client = new xmlrpc_client(RT_DIR, RT_HOST, RT_PORT);
		$this->multicall = new multicall($this->client, $this->data);

		if(RT_AUTH)
			$this->client->setCredentials(RT_USER, RT_PASSWD);
    
		$this->client->return_type = 'phpvals';
    $this->client->no_multicall = NO_MULTICALL;
    	
    $message = new xmlrpcmsg("system.pid");
		$result = $this->client->send($message);

		if($result->errno != 0)
		{
			$this->addMessage($this->_str['err_conn']);
			return false;
		} else {
			$this->setTorrents();
			return true;
		}
	}
	/* Initializes torrent objects */
	protected function setTorrents() {
		$message = new xmlrpcmsg("d.multicall", array(new xmlrpcval('default', 'string'),new xmlrpcval('d.get_hash=', 'string')));
		$result = $this->client->send($message);
		if(is_array($result->val))
		{
			foreach($result->val as $hash)
			{
				$this->torrents[$hash[0]] = new torrent($hash[0], $this->client, $this->multicall, $this->data[$hash[0]]);
			}
			/* Mark torrents as public/private and assign owners */
			$private_torrents = $this->getPrivate();
			$hashes = $this->getHashes();
			foreach($hashes as $hash)
			{
				if(array_key_exists($hash, $private_torrents))
				{
					$this->data[$hash]['private'] = true;
					$this->data[$hash]['owner'] = $private_torrents[$hash];
				} else {
					$this->data[$hash]['private'] = false;
					$this->data[$hash]['owner'] = 0;
				}
			}
		}
	}
	protected function getHashes()
	{
		return array_keys($this->torrents);
	}
	protected function erase_db($hash)
	{
		$sql = "delete from torrents where hash = '" . $hash . "'";
		$this->_db->query($sql);
	}
	protected function setPerm()
	{
		$this->admin = false;
	}
	/* xmlrpc rtorrent admin functions (not torrent related ) */
	protected function set_download_rate($limit)
	{
		$message = new xmlrpcmsg("set_download_rate", array(new xmlrpcval($limit, 'int')));
		$result = $this->client->send($message);
		
		if($this->checkError($result))
		{
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	protected function set_upload_rate($limit)
	{
		$message = new xmlrpcmsg("set_download_rate", array(new xmlrpcval($limit, 'int')));
		$result = $this->client->send($message);
		
		if($this->checkError($result))
		{
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
}
?>