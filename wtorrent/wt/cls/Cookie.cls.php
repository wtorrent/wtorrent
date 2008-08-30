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
class Cookie extends rtorrent
{
	private $info = array();
	public function construct()
	{
		if(!$this->setClient())
		{
			return false;
		}

		$action = empty($this->_post['action']) ? 'listCookies' : $this->_post['action'];

		switch ($action) {
		case 'eraseCookie':
		case 'addCookie':
			$this->$action();
		break;
		}

		$this->info = $this->_db->arrayQuery("SELECT * FROM cookie WHERE userid = {$this->getIdUser()}", SQLITE_ASSOC);
	}
	public function getCookies()
	{
		return $this->info;
	}
	private function addCookie()
	{
		if (empty($this->_post['cookie_host']) || empty($this->_post['cookie_value']))
		{
			$this->addMessage($this->_str['err_add_cookie']);
			return;
		}
		$sql = sprintf(
			"INSERT INTO cookie (userid, hostname, value) VALUES(%d, '%s', '%s')",
			$this->getIdUser(),
			sqlite_escape_string($this->_post['cookie_host']),
			sqlite_escape_string($this->_post['cookie_value'])
		);
		$this->_db->queryExec($sql);
		$this->addMessage($this->_str['info_add_cookie']);
	}
	private function eraseCookie()
	{
		if (empty($this->_post['cookie_id']))
		{
			$this->addMessage($this->_str['err_erase_cookie']);
			return;
		}

		$sql = sprintf(
			"DELETE FROM cookie WHERE userid = %d AND id = %d",
			$this->getIdUser(),
			$this->_post['cookie_id']
		);
		$this->_db->query($sql);
		$this->addMessage($this->_str['info_erase_cookie']);
	}
}
?>
