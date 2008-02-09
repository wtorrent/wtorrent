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
	private  $trackers;
	private  $info_trackers = array('', '',
								't.get_url=', 
    							't.get_normal_interval=',
    							't.get_scrape_time_last=', 
    							't.get_scrape_complete=',
    							't.get_scrape_incomplete=',
    							't.is_enabled=');
   

    public function construct()
	{
		$this->hash = $this->_request['hash'];
		
		if(!$this->setClient())
			return false;
			
		if(isset($this->_request['ch_tr']) && count($this->_request['trackers']) > 0) $this->changeEnabled($this->_request['trackers'], $this->_request['active'], $this->hash);
		$this->getTorrents($this->_request['hash']);
		
	}

	public function getHash()
	{
		return $this->hash;
	}
	public function getTrackers()
	{
		return $this->trackers;
	}
	public function getName()
	{
		return $this->details['name'];
	}
	public function getTorrent()
	{
		return $this->details['torrent_file'];
	}
	public function getDataPath()
	{
		return $this->details['data_path'];
	}
	public function getPercent()
	{
		return $this->details['percent'];
	}
	public function getRatio()
	{
		return $this->details['ratio'];
	}
	public function getSize()
	{
		return $this->getCorrectUnits($this->details['size_in_chunks'] * $this->details['chunk_size']);
	}
	public function getDone()
	{
		return $this->getCorrectUnits($this->details['completed_chunks'] * $this->details['chunk_size']);
	}
	public function getUp()
	{
		return $this->getCorrectUnits($this->details['bytes_up']);
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
        return round($size, 2) .  $size_units;

    }
	
	private function getTorrents($hash)
    {
    	//define("XMLRPC_DEBUG", 1);
    	
    	$this->info_trackers[0] = $hash;
    	
		foreach($this->info_trackers as $param)
			$array_post[] = new xmlrpcval($param, 'string');
		
		$message = new xmlrpcmsg("t.multicall", $array_post);
		$result = $this->client->send($message);
    	//print_r($result);
    	foreach($result->val as $key => $tracker)
    	{
    		if(SCRAMBLE === true)
    			$tracker[0] = $this->scramble($tracker[0]);
    		
    		$sc_time = time() - $tracker[2];
    		//echo $sc_time . " " . $tracker[1] . " " . time() . " " . $tracker[2];
    		if($sc_time > $tracker[1])
    			$enabled = 0;
    		else
    			$enabled = 1;
    			
    		$this->trackers[$key]['url'] = $tracker[0];
    		$this->trackers[$key]['scrape_completed'] = $tracker[3];
    		$this->trackers[$key]['scrape_incomplete'] = $tracker[4];
    		$this->trackers[$key]['enabled'] = $tracker[5];
    	}
    	//print_r($this->files);
    	//XMLRPC_debug_print();
    }
    private function changeEnabled($trackers, $enabled, $hash)
    {
    	$t_index = array_keys($trackers);
    	//print_r($f_index);
    	
    	foreach($t_index as $param)
			$array_post[] = new xmlrpcmsg('t.set_enabled', array(new xmlrpcval($hash, 'string'), new xmlrpcval($param, 'int'), new xmlrpcval($enabled, 'int')));
		
		//print_r($array_post);	
		$responses = $this->client->multicall($array_post);
    }
}
?>