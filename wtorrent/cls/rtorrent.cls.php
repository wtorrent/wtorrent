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
	public function setClient()
	{
		$this->client = new xmlrpc_client(RT_DIR, RT_HOST, RT_PORT);
		//$this->client->setDebug(2);
		if(RT_AUTH)
			$this->client->setCredentials(RT_USER, RT_PASSWD);
    	$this->client->return_type = 'phpvals';
    	$this->client->no_multicall = NO_MULTICALL;
    	
    	$message = new xmlrpcmsg("system.pid");
		$result = $this->client->send($message);
		// print_r($result);
		if($result->errno != 0)
		{
			$this->addMessage($this->_str['err_conn']);
			return false;
		} else
			return true;
	}
	protected function setPerm()
	{
		$this->admin = false;
	}
}
?>