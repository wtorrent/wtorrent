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
class Peers extends rtorrent
{
	private $hash;

 	public function construct()
	{
		$this->hash = $this->_request['hash'];
		
		if(!$this->setClient())
			return false;
			
		$array_p = array('p.get_address', 'p.get_down_rate', 'p.get_up_rate','p.is_incoming', 'p.get_completed_percent', 'p.is_encrypted', 'p.get_peer_rate', 'p.get_client_version');
		$this->multicall->p_multicall($this->hash, $array_p);
	}
	public function getHash()
	{
		return $this->hash;
	}
	public function getPeers()
	{
		$num = $this->torrents[$this->hash]->get_peers_connected();
		for($i = 0; $i < $num; $i++)
  	{		
  		$peers[$i]['ip'] = $this->torrents[$this->hash]->p_get_address($i);
			$peers[$i]['up_peer'] = round($this->torrents[$this->hash]->p_get_down_rate($i)/1024, 2);
			$peers[$i]['down_peer'] = round($this->torrents[$this->hash]->p_get_up_rate($i)/1024, 2);
			$peers[$i]['incoming'] = $this->torrents[$this->hash]->p_is_incoming($i);
  		$peers[$i]['done'] = $this->torrents[$this->hash]->p_get_completed_percent($i);
  		$peers[$i]['encrypted'] = $this->torrents[$this->hash]->p_is_encrypted($i);
  		$peers[$i]['down'] = round($this->torrents[$this->hash]->p_get_peer_rate($i)/1024, 2);
  		$peers[$i]['client'] = $this->torrents[$this->hash]->p_get_client_version($i);
  	}
		return $peers;
	}
}
?>