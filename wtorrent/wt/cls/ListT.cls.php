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
class ListT extends rtorrent
{
	// Units for formatETA()
	private static $PERIODS = array(
		'days'      => 86400,
		'hours'     => 3600,
		'minutes'   => 60,
		'seconds'   => 1
	);
	// Upper limit for formatETA() specifying when to display infinity
	private static $INFLIMIT = 1209600; // 14 days

	private static $DOWNLOAD_VALUES = array(
		'd.get_name',
		'd.get_down_rate',
		'd.get_up_rate',
		'd.get_chunk_size',
		'd.get_completed_chunks',
		'd.get_size_chunks',
		'd.get_state',
		'd.get_peers_accounted',
		'd.get_peers_complete',
		'd.is_hash_checking',
		'd.get_ratio',
		'd.get_tracker_size',
		'd.is_active',
		'd.is_open',
		'd.get_message',
		'd.get_creation_date',
		'd.get_left_bytes',
		'd.get_size_bytes'
	);
	private static $TORRENT_VALUES = array(
		't.get_scrape_complete',
		't.get_scrape_incomplete'
	);

	private $view;
 
	public function construct()
	{
		switch($this->_request['view'])
		{
			case 'public':
				$this->view = 'public';
				$this->rtorrent_view = 'default';
				break;
			case 'private':
				$this->view = 'private';
				$this->rtorrent_view = 'default';
				break;
			default:
				if(isset($this->_request['view']))
				{
					$this->view = 'public';
					$this->rtorrent_view = $this->_request['view'];
				} else {
					$this->view = 'public';
					$this->rtorrent_view = 'default';
				}
				break;
		}
		
		if (!$this->setClient())
		{
			return false;
		}
		
		/* d multicall with all the necessary info to generate the torrent list */
 		$this->multicall->d_multicall(self::$DOWNLOAD_VALUES, $this->rtorrent_view);

		// t multicall
		$hashes = $this->getHashes(); // Retrieve hashes
		if(!empty($hashes))
		{
			// Order hashes
			// Set key and order to name if omitted
			$this->_request['sort'] = empty($this->_request['sort']) ? self::$SORT_KEYS[0] : $this->_request['sort'];
			$this->_request['order'] = empty($this->_request['order']) ? self::$SORT_ORDERS[0] : $this->_request['order'];

			// carry out the sorting
			// sortTorrentsBy will consolidate the input
			
			foreach($hashes as $hash)
			{
				$this->multicall->t_multicall($hash, self::$TORRENT_VALUES);
			}
			
			$this->sortTorrentsBy($this->_request['sort'], $this->_request['order']);
			if($this->_tpl == 'ajax')
				$this->setJSON();
		}
	}
	
	public function setJSON()
	{
		$json_data = new stdClass();
		$json_data->space_used_total = $this->getUsedSpace() . '/' . $this->getTotalSpace();
		$json_data->prog_bar = $this->getUsedPercent();
		$json_data->space_free = $this->getFreeSpace();
		$json_data->dw_rate = $this->getDownload();
		$json_data->up_rate = $this->getUpload();
		header('X-JSON: ('.$this->_json->encode($json_data).')');
	}

	public function getView()
	{
		return $this->view;
	}
	public function getPublicHashes()
	{
		$hashes = $this->getHashes();
		if(!empty($hashes))
		{
			foreach($hashes as $hash)
				if($this->torrents[$hash]->get_private() === false)
					$return[] = $hash;
		}		
		return $return;
	}
	public function getPublicHashesNum()
	{
		$hashes = $this->getHashes();
		$i = 0;
		if(!empty($hashes))
		{
			foreach($hashes as $hash)
				if($this->torrents[$hash]->get_private() === false)
					$i++;
		}		
		return $i;
	}
	public function getPrivateHashes()
	{
		$hashes = $this->getHashes();
		if(!empty($hashes))
		{
			foreach($hashes as $hash)
				if(($this->torrents[$hash]->get_private() === true) && ($this->torrents[$hash]->get_owner() == $this->getIdUser()))
					$return[] = $hash;
		}
		return $return;
	}
	public function getPrivateHashesNum()
	{
		$hashes = $this->getHashes();
		$i = 0;
		if(!empty($hashes))
		{
			foreach($hashes as $hash)
				if(($this->torrents[$hash]->get_private() === false) && ($this->torrents[$hash]->get_owner() == $this->getIdUser()))
				$i++;
		}		
		return $i;
	}
	public function getViews()
	{
		$array_filter = array('main', 'default', 'scheduler', 'name');
		$views = $this->view_list();
		if(!empty($views))
		{
			foreach($views as $view)
			{
				if(!in_array($view, $array_filter))
					$return[] = $view; 
			}
		}
		return $return;
	}
	public function getName($hash)
	{
		return $this->torrents[$hash]->get_name();
	}
	public function getState($hash)
	{
		return $this->torrents[$hash]->get_state();
	}
	public function getOpen($hash)
	{
		return $this->torrents[$hash]->is_open();
	}
	public function getConnPeers($hash)
	{
		return $this->torrents[$hash]->get_peers_accounted();
	}
	public function getConnSeeds($hash)
	{
		return $this->torrents[$hash]->get_peers_complete();
	}
	public function getTotalPeers($hash)
	{
		$peers = 0;
		$num = $this->torrents[$hash]->get_tracker_size();
		
		for($i = 0; $i < $num; $i++)
		{
			$t_peers = $this->torrents[$hash]->t_get_scrape_incomplete($i);
			$peers += $t_peers;
		}
			
		return $peers;
	}
	public function getTotalSeeds($hash)
	{
		$seeds = 0;
		$num = $this->torrents[$hash]->get_tracker_size();
		
		for($i = 0; $i < $num; $i++)
		{
			$t_seeds = $this->torrents[$hash]->t_get_scrape_complete($i);
			$seeds += $t_seeds;
		}
		
		return $seeds;
	}
	public function getDownRate($hash)
	{
		return round($this->torrents[$hash]->get_down_rate()/1024,2);
	}
	public function getUpRate($hash)
	{
		return round($this->torrents[$hash]->get_up_rate()/1024,2);
	}
	public function getPercent($hash)
	{
		return floor(($this->torrents[$hash]->get_completed_chunks()/$this->torrents[$hash]->get_size_chunks())*100);
	}
	public function getRatio($hash)
	{
		return round($this->torrents[$hash]->get_ratio()/1000,2);
	}
	public function isHashChecking($hash)
	{
		return $this->torrents[$hash]->is_hash_checking();
	}
	public function getETA($hash)
	{
		// Complete, hence remaining time is 0
		if ($this->getPercent($hash) == 100)
		{
			return $this->formatETA(0);
		}
		// No download process, hence time is infinity
		if ($this->torrents[$hash]->get_down_rate() <= 0) {
			return $this->formatETA(self::$INFLIMIT);
		}

		// int overflow :p
		if ($this->torrents[$hash]->get_left_bytes() == 2147483647)
		{
			$left = $this->torrents[$hash]->get_size_chunks() - $this->torrents[$hash]->get_completed_chunks();

			// do we have bcmath, then use that
			if (function_exists('bcmul') && function_exists('bcdiv'))
		       	{
				return $this->formatETA(
					bcdiv(
						bcmul(
							$left,
							$this->torrents[$hash]->get_chunk_size()
						),
						$this->torrents[$hash]->get_down_rate()
					)
				);
			}

			// this might be pretty inaccurate, however we got no other option :p
			return $this->formatETA($left / ($this->torrents[$hash]->get_down_rate() / $this->torrents[$hash]->get_chunk_size()));
		}

		return $this->formatETA($this->torrents[$hash]->get_left_bytes() / $this->torrents[$hash]->get_down_rate());
	}
	public function getSize($hash)
	{
		return $this->getCorrectUnits($this->torrents[$hash]->get_size_chunks() * $this->torrents[$hash]->get_chunk_size());
	}
	public function getDone($hash)
	{
		return $this->getCorrectUnits($this->torrents[$hash]->get_completed_chunks() * $this->torrents[$hash]->get_chunk_size());
	}
	public function getTstate($hash)
	{
		if($this->torrents[$hash]->get_state() == 0)
		{
			$return = 'stopped';
		} else {
			if($this->getPercent($hash) != 100){
				$return = 'downloading';
			} else {
				$return = 'seeding';
			}
		}
		if($this->torrents[$hash]->is_open() != 1)
			$return = 'closed';

		if(($this->torrents[$hash]->get_message() != '') && ($this->torrents[$hash]->get_message() != 'Tracker: [Tried all trackers.]'))
			$return = 'message';

		if($this->torrents[$hash]->is_hash_checking() == 1)
			$return = 'chash';
			
		return $return;
	}
	public function getTstyle($hash)
	{
		switch($this->getTstate($hash))
		{
			case 'downloading':
			$return = 'green';
			break;
			case 'stopped':
			$return = 'black';
			break;
			case 'seeding':
			$return = 'blue';
			break;
			case 'closed':
			$return = 'black';
			break;
			case 'message':
			$return = 'red';
			break;
			case 'chash':
			$return = 'yellow';
			break;
		}
		return $return;
	}
	public function getMessage($hash)
	{
		return $this->torrents[$hash]->get_message();
	}
	public function getTooltipText($hash)
 	{
		if ($this->getTstate($hash) != 'message')
		{
			return null;
		}
		return $this->getMessage($hash);
	}
	public function getCreationDate($hash)
 	{
		return $this->torrents[$hash]->get_creation_date();
	}

	private function formatETA($time)
	{
		$seconds = intval($time);

		if ($seconds <= 0)
		{
			return '--';
		}

		// > 2 weeks = infinite ETA
		if ($seconds > self::$INFLIMIT)
	       	{
			return '∞';
		}

		$c = 0;
		$rv = '';
		foreach (self::$PERIODS as $period => $value) 
		{
			$count = floor($seconds / $value);
			if ($count == 0)
		       	{
				continue;
			}
			$seconds = $seconds % $value;

			$rv .= $count . substr($period, 0,  1) . ' ';

			// display only the first two non-zero periodic units and values
			if (++$c >= 2)
		       	{
				break;
			}
		}
		return $rv;
	}
}
?>
