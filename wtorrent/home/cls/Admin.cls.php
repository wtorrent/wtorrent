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
*/
class Admin extends rtorrent
{
	public function construct()
	{
		if(isset($this->_request['adduser'])) $this->addUser($this->_request['user'], $this->_request['passwd'], $this->_request['admin'], $this->_request['default_dir'], $this->_request['force_dir']);
		if(isset($this->_request['delete'])) $this->deleteUsers($this->_request['users']);
		
		if(!$this->setClient())
			return false;
		
		if(isset($this->_request['ch_dw'])) $this->setDownLimit($this->_request['down_rate']);
		if(isset($this->_request['ch_up'])) $this->setUploadLimit($this->_request['up_rate']);
	}
	public function getUpLimit()
	{
		$message = new xmlrpcmsg("get_upload_rate");
		$result = $this->client->send($message);
		return round($result->val/1024, 1);
	}
	public function getDownLimit()
	{
		$message = new xmlrpcmsg("get_download_rate");
		$result = $this->client->send($message);
		return round($result->val/1024, 1);
	}

	public function showUsers()
    {
		$sql = "SELECT user, id, admin, dir, force_dir FROM tor_passwd";
		$res = $this->_db->query( $sql );
		$result = $res->fetchAll();
		$num = count($result);
		//print_r($result);
		return $result;
    }
    public function deleteUsers($users)
    {
		if(count($users) > 0)
		{
    		$list=array_keys($users);
			$sql = "DELETE FROM tor_passwd where id IN (".implode(',',$list).")";
			$res = $this->_db->query( $sql );
			$this->addMessage($this->_str['info_users_deleted']);
		} else {
			$this->addMessage($this->_str['err_users_nosel']);
		}
    }
    public function addUser($user, $passwd, $admin, $dir, $force_dir)
    {
		if($user != '' && $passwd != '')
		{
			if($admin == 'on') $admin = 1;
            if($force_dir == 'on') $force_dir = 1;
    		$sql = "insert into tor_passwd(user, passwd, admin, dir, force_dir) values('$user', '" . md5($passwd) . "', '" . $admin . "', '$dir', '$force_dir')";
			$this->_db->query( $sql );
			$this->addMessage($this->_str['info_users_added']);
		} else {
			$this->addMessage($this->_str['err_users_add']);
		}
		
    }
    protected function setPerm()
    {
    	$this->admin = true;
    }
    private function setDownLimit($limit)
    {
    	$message = new xmlrpcmsg("set_download_rate", array(new xmlrpcval($limit*1024, 'int')));
		$result = $this->client->send($message);
		//print_r($result);
		if($result->errno == 0)
			$this->addMessage($this->_str['info_down_limit']);
		else
			$this->addMessage($this->_str['err_down_limit']);	
    }
    private function setUploadLimit($limit)
    {
    	$message = new xmlrpcmsg("set_upload_rate", array(new xmlrpcval($limit*1024, 'int')));
		$result = $this->client->send($message);
		if($result->errno == 0)
			$this->addMessage($this->_str['info_up_limit']);
		else
			$this->addMessage($this->_str['err_up_limit']);	
    }
}