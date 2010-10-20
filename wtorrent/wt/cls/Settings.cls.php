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
class Settings extends rtorrent
{
	public function construct()
	{
		if(isset($this->_request['ch_passwd'])) $this->changePasswd($this->_request['old_passwd'], $this->_request['passwd1'], $this->_request['passwd2']);
		if(!$this->setClient())
			return false;
	}

	public function changePasswd($old_passwd, $passwd1, $passwd2)
	{
		$uid = $this->getIdUser();
		$passwd = $this->_db->queryColumn('SELECT passwd FROM tor_passwd WHERE id = ?', $uid);
		if (md5($old_passwd) != $passwd)
		{
			$this->addMessage($this->_str['old_passwd_wrong']);
			return;
		}
		if (!Settings::passwdSatisfy($passwd1))
		{
			$this->addMessage($this->_str['new_passwd_wrong']);
			return;
		}
		if ($passwd1 != $passwd2)
		{
			$this->addMessage($this->_str['passwd_differ']);
			return;
		}

		$this->_db->modify('UPDATE tor_passwd SET passwd = ? WHERE id = ?',
			md5($passwd1),
			$uid
			);
		$this->addMessage($this->_str['passwd_changed']);
	}

	private static function passwdSatisfy($passwd)
	{
		if (strlen($passwd) < 6)
			return false;
		return true;
	}
}
