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
	private  $view;
	private  $torrents = array();
    private  $info_dowload = array('default', 
    							'd.get_hash=', 
    							'd.get_name=',
								'd.get_down_rate=',
								'd.get_up_rate=',
								'd.get_chunk_size=',
								'd.get_completed_chunks=',
								'd.get_size_chunks=',
								'd.get_state=',
								'd.get_peers_accounted=',
								'd.get_peers_complete=',
								'd.is_hash_checking=',
								'd.get_ratio=',
								'd.get_tracker_size=',
								'd.is_active=',
								'd.is_open=',);
	private $info_tracker = array('',
								"",
								't.get_scrape_complete=',
								't.get_scrape_incomplete=');

    public function construct()
	{
		if($this->_request['view'] == 'public') $this->view = 'public';
		if($this->_request['view'] == 'private') $this->view = 'private';
		if(!isset($this->_request['view'])) $this->view = 'public';
		
		if(!$this->setClient())
			return false;
		
		if(isset($this->_request['start'])) $this->start($this->_request['start']);
		if(isset($this->_request['stop'])) $this->stop($this->_request['stop']);
		if(isset($this->_request['erase'])) $this->erase($this->_request['erase']);
		
		$this->getTorrents();
	}

	public function getView()
	{
		return $this->view;
	}
	public function getPublicHashes()
	{
		$tor_hashes = array_keys($this->torrents);
		foreach($tor_hashes as $hash)
			if($this->torrents[$hash]['private'] === false)
				$return[] = $hash;
				
		return $return;
	}
	public function getPublicHashesNum()
	{
		$tor_hashes = array_keys($this->torrents);
		$i = 0;
		foreach($tor_hashes as $hash)
			if($this->torrents[$hash]['private'] === false)
				$i++;
				
		return $i;
	}
	public function getPrivateHashes()
	{
		$tor_hashes = array_keys($this->torrents);
		foreach($tor_hashes as $hash)
			if($this->torrents[$hash]['private'] === true)
				$return[] = $hash;
				
		return $return;
	}
	public function getPrivateHashesNum()
	{
		$tor_hashes = array_keys($this->torrents);
		$i = 0;
		foreach($tor_hashes as $hash)
			if($this->torrents[$hash]['private'] === true)
				$i++;
				
		return $i;
	}
	public function getName($hash)
	{
		return $this->torrents[$hash]['name'];
	}
	public function getState($hash)
	{
		return $this->torrents[$hash]['state'];
	}
	public function getOpen($hash)
	{
		return $this->torrents[$hash]['is_open'];
	}
	public function getActive($hash)
	{
		return $this->torrents[$hash]['is_active'];
	}
	public function getConnPeers($hash)
	{
		return $this->torrents[$hash]['peers'];
	}
	public function getConnSeeds($hash)
	{
		return $this->torrents[$hash]['seeds'];
	}
	public function getTotalPeers($hash)
	{
		return $this->torrents[$hash]['peers_scrape'];
	}
	public function getTotalSeeds($hash)
	{
		return $this->torrents[$hash]['seeds_scrape'];
	}
	public function getDownRate($hash)
	{
		return $this->torrents[$hash]['down_rate'];
	}
	public function getUpRate($hash)
	{
		return $this->torrents[$hash]['up_rate'];
	}
	public function getPercent($hash)
	{
		return $this->torrents[$hash]['percent'];
	}
	public function getRatio($hash)
	{
		return $this->torrents[$hash]['ratio'];
	}
	public function isHashChecking($hash)
	{
		return $this->torrents[$hash]['is_hash_checking'];
	}
	public function getETA($hash)
	{
		return $this->torrents[$hash]['ETA'];
	}
	public function getSize($hash)
	{
		return $this->getCorrectUnits($this->torrents[$hash]['size_in_chunks'] * $this->torrents[$hash]['chunk_size']);
	}
	public function getDone($hash)
	{
		return $this->getCorrectUnits($this->torrents[$hash]['completed_chunks'] * $this->torrents[$hash]['chunk_size']);
	}
	private function getCorrectUnits($size)
    {
		$size_units = 'bytes';
		if($size >= 1024)
		{
	    	$size /= 1024;
	    	$size_units = 'Kb';
		}
		if($size >= 1024)
		{
            $size /= 1024;
            $size_units = 'Mb';
        }
		if($size >= 1024)
        {
            $size /= 1024;
            $size_units = 'Gb';
        }
        return round($size, 1) .  $size_units;

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
    
	private function getTorrents()
    {
    	//define("XMLRPC_DEBUG", 1);
    	//$this->client->setDebug(2);
    	foreach($this->info_dowload as $param)
			$array_post[] = new xmlrpcval($param, 'string');
		
		//print_r(XMLRPC_prepare($array_post));
		//print_r(XMLRPC_prepare($array_multi)); 
		
    	//$result = XMLRPC_request(RT_HOST , '/RPC2', 'd.multicall' ,$array_post);
		$message = new xmlrpcmsg("d.multicall", $array_post);
		$result = $this->client->send($message);
    	//print_r($result);
    	// Multicall for tracker info (reduces load time on big torrent list
    	$array_post = array();
    	foreach($this->info_tracker as $param)
				$array_post[] = new xmlrpcval($param, 'string');
		
    	if(count($result->val)) {
    		foreach($result->val as $torrent)
    		{
    			$array_post[0] = new xmlrpcval($torrent[0], 'string');
    			$messages[] = new xmlrpcmsg("t.multicall", $array_post);
    		}
    		if(count($messages) > 0)
    			$Tresponses = $this->client->multicall($messages);
    		//print_r($responses);
    		foreach($result->val as $key => $torrent)
    		{
    			// Check if the torrent is private and if the user can see it
    			$private = false;
    			$pr_torrent = $this->getPrivate();
    			if(array_key_exists($torrent[0], $pr_torrent))
    			{
    				$private = true;
    				if($pr_torrent[$torrent[0]] != $this->getIdUser())
    					continue;
    			}
				$this->torrents[$torrent[0]]['private'] = $private;
				$this->torrents[$torrent[0]]['name'] = $torrent[1];
				$this->torrents[$torrent[0]]['down_rate'] = round($torrent[2]/1024,2);
				$this->torrents[$torrent[0]]['up_rate'] = round($torrent[3]/1024,2);
				$this->torrents[$torrent[0]]['chunk_size'] = $torrent[4];
				$this->torrents[$torrent[0]]['completed_chunks'] = $torrent[5];
				$this->torrents[$torrent[0]]['size_in_chunks'] = $torrent[6];
				$this->torrents[$torrent[0]]['state'] = $torrent[7];
				$this->torrents[$torrent[0]]['peers'] = $torrent[8];
				$this->torrents[$torrent[0]]['seeds'] = $torrent[9];
				$this->torrents[$torrent[0]]['is_hash_checking'] = $torrent[10];
				$this->torrents[$torrent[0]]['ratio'] = round($torrent[11]/1000,2);
				$this->torrents[$torrent[0]]['num_trackers'] = $torrent[12];
				$this->torrents[$torrent[0]]['is_active'] = $torrent[13];
				$this->torrents[$torrent[0]]['is_open'] = $torrent[14];

				$this->torrents[$torrent[0]]['percent'] = floor(($this->torrents[$torrent[0]]['completed_chunks']/$this->torrents[$torrent[0]]['size_in_chunks'])*100);

				$this->torrents[$torrent[0]]['ETA'] = '--';
				if(($this->torrents[$torrent[0]]['percent'] != 100) && ($this->torrents[$torrent[0]]['down_rate'] != 0))
					$this->torrents[$torrent[0]]['ETA'] = $this->formatETA(ceil((($this->torrents[$torrent[0]]['size_in_chunks'] - $this->torrents[$torrent[0]]['completed_chunks'])*$this->torrents[$torrent[0]]['chunk_size']/1024)/$this->torrents[$torrent[0]]['down_rate']*1000));

				if(SCRAMBLE === true)
					$this->torrents[$torrent[0]]['name'] = $this->scramble($this->torrents[$torrent[0]]['name']);

				/*$array_post = array();
				$this->info_tracker[0] = $torrent[0];*/

				/*foreach($this->info_tracker as $param)
					$array_post[] = new xmlrpcval($param, 'string');

				$message = new xmlrpcmsg("t.multicall", $array_post);*/
				//$resultT = ;
				//$resultT = XMLRPC_request(RT_HOST, '/RPC2', 't.multicall' ,$array_post);
				//print_r($Tresponses);
				foreach($Tresponses[$key]->val as $tracker)
				{
					$this->torrents[$torrent[0]]['seeds_scrape'] += $tracker[0];
					$this->torrents[$torrent[0]]['peers_scrape'] += $tracker[1];
				}
    		}
			
    	}
    	//print_r($this->torrents);
    	//XMLRPC_debug_print();*/
    }
    private function formatETA($time)
    {
		$time = $time/1000;
		$sec = sprintf("%02d",floor(($time/60 - floor($time/60))*60));
		$min =  sprintf("%02d",floor(($time/3600 - floor($time/3600))*60));
		$hour =  sprintf("%02d",floor(($time/216000 - floor($time/216000))*60));
	
		if($hour > 23)
		{
			$days = floor($hour/24) . "d ";
			$hour = sprintf("%02d",floor(($hour/24 - floor($hour/24))*24));
		} else {
			$days = '';
		}

		return $days . $hour.':'.$min.':'.$sec;
    }
}
?>