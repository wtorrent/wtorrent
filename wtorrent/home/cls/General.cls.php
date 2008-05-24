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
class General extends rtorrent
{
	private  $hash;
	
	public function construct()
	{
		$this->hash = $this->_request['hash'];
    	
		if(!$this->setClient())
			return false;
	}
	////////////////////////////////////////////////// C O N S U L T O R A S //////////////////////////////////////////////////
	public function getHash()
	{
		return $this->hash;
	}
	public function getName()
	{
		return $this->torrents[$hash]->get_name();
	}
	public function getTorrent()
	{
		return '/' . ltrim($this->torrents[$this->hash]->get_tied_to_file(), '/');
	}
	public function getDataPath()
	{
		return $this->torrents[$this->hash]->get_base_path();
	}
	public function getPercent()
	{
		return floor(($this->torrents[$this->hash]->get_completed_chunks() / $this->torrents[$this->hash]->get_size_chunks())*100);
	}
	public function getRatio()
	{
		return round($this->torrents[$this->hash]->get_ratio()/1000,2);
	}
	public function getSize()
	{
		return $this->getCorrectUnits($this->torrents[$this->hash]->get_size_chunks() * $this->torrents[$this->hash]->get_chunk_size());
	}
	public function getDone()
	{
		return $this->getCorrectUnits($this->torrents[$this->hash]->get_completed_chunks() * $this->torrents[$this->hash]->get_chunk_size());
	}
	public function getUp()
	{
		return $this->getCorrectUnits($this->torrents[$this->hash]->get_completed_chunks() * $this->torrents[$this->hash]->get_chunk_size() * $this->getRatio());
	}
	public function getMaxPeers()
	{
		return $this->torrents[$this->hash]->get_peers_max();
	}
	public function getMinPeers()
	{
		return $this->torrents[$this->hash]->get_peers_min();
	}
	public function getPriorityStr()
	{
		switch($this->torrents[$this->hash]->d_get_priority())
		{
			case 1:
				return $this->_str['low'];
				break;
			case 2:
				return $this->_str['normal'];
				break;
			case 3:
				return $this->_str['high'];
				break;
			case 0:
				return $this->_str['idle'];
				break;
		}
	}
	public function getPriorities()
	{
		return array('0' => $this->_str['idle'], '1' => $this->_str['low'], '2' => $this->_str['normal'], '3' => $this->_str['high']);
	}
	public function getPriority()
	{
		return $this->torrents[$this->hash]->d_get_priority();
	}
	public function getMessage()
	{
		return $this->torrents[$this->hash]->get_message();
	}
}
?>