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
class Tracker extends rtorrent
{
	private $hash;

	public function construct()
	{
		$this->hash = $this->_request['hash'];
		
		if(!$this->setClient())
			return false;
			
		$array_t = array('t.get_url', 't.get_normal_interval','t.get_scrape_time_last', 't.get_scrape_complete','t.get_scrape_incomplete','t.is_enabled');
		$this->multicall->t_multicall($this->hash, $array_t);
	}
	public function getHash()
	{
		return $this->hash;
	}
	public function getTrackers()
	{
		$num = $this->torrents[$this->hash]->get_tracker_size();
		for($i = 0; $i < $num; $i++)
		{
			$trackers[$i]['url'] = $this->torrents[$this->hash]->t_get_url($i);
			$trackers[$i]['scrape_completed'] = $this->torrents[$this->hash]->t_get_scrape_complete($i);
			$trackers[$i]['scrape_incomplete'] = $this->torrents[$this->hash]->t_get_scrape_incomplete($i);
			$trackers[$i]['enabled'] = $this->torrents[$this->hash]->t_is_enabled($i);
		}
		return $trackers;
	}
}
?>