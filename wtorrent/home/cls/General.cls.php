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
	private  $details;
	private  $hash;
	private  $info_general = array('d.get_name',
									'd.get_tied_to_file',
									'd.get_base_path',
									'd.get_down_rate',
									'd.get_up_rate',
									'd.get_chunk_size',
									'd.get_completed_chunks',
									'd.get_size_chunks',
									'd.get_ratio',
									'd.get_peers_max',
									'd.get_peers_min',
									'd.get_priority',
									'd.get_message');
    /////////////////////////////////// C O N S T R U C T O R A S  Y  D E S T R U C T O R A ///////////////////////////////////

    public function construct()
    {
		$this->hash = $this->_request['hash'];
    	
		if(!$this->setClient())
			return false;
		
		if(isset($this->_request['ch_pr'])) $this->changePriority($this->hash, $this->_request['priority']);
		
    	$this->getTorrents($this->hash);
	}

	////////////////////////////////////////////////// C O N S U L T O R A S //////////////////////////////////////////////////
	public function getHash()
	{
		return $this->hash;
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
	public function getMaxPeers()
	{
		return $this->details['peers_max'];
	}
	public function getMinPeers()
	{
		return $this->details['peers_min'];
	}
	public function getPriorityStr()
	{
		switch($this->details['priority'])
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
		return $this->details['priority'];
	}
	public function getMessage()
	{
		return $this->details['message'];
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
	//////////////////////////////////////////////// M O D I F I C A D O R A S ////////////////////////////////////////////////
	private function getTorrents($hash)
    {
    	foreach($this->info_general as $param)
			$array_post[] = new xmlrpcmsg($param, array(new xmlrpcval($hash, 'string')));
			
		$responses = $this->client->multicall($array_post);	 
    	
    	//print_r($responses);
		
	    if(SCRAMBLE === true)
	    {
	    	$tor_name[1] = $this->scramble($tor_name[1]);
	    	$tor_file[1] = $this->scramble($tor_file[1]);
	    	$tor_base_path[1] = $this->scramble($tor_base_path[1]);
	    }
	    
	    $this->details['id'] = $tor_id;
	    $this->details['name'] = $responses[0]->val;
	    $this->details['torrent_file'] = '/' . ltrim($responses[1]->val, '/');
	    $this->details['data_path'] = $responses[2]->val;
	    $this->details['bytes_done'] = $responses[5]->val * $responses[6]->val;
	    $this->details['chunk_size'] = $responses[5]->val;
	    $this->details['completed_chunks'] = $responses[6]->val;
	    $this->details['size_in_chunks'] = $responses[7]->val;
	    $this->details['peers_max'] = $responses[9]->val;
	    $this->details['peers_min'] = $responses[10]->val;
	    $this->details['priority'] = $responses[11]->val;
	    $this->details['message'] = $responses[12]->val;
	    $this->details['missing_chunks'] = $this->details['size_in_chunks'] - $this->details['completed_chunks'];
	    $this->details['missing_bytes'] = $this->details['missing_chunks'] * $this->details['chunk_size'];
	    //$this->details['seeds'] = $tor_completed[1];
	    //$this->details['peers'] = $tor_peers[1];
	    $this->details['ratio'] = round($responses[8]->val/1000,2);
	    $this->details['bytes_up'] = $this->details['bytes_done'] * $this->details['ratio'];
	    $this->details['bytes_total'] = $this->details['chunk_size'] * $this->details['size_in_chunks'];
	    //$this->details['num_trackers'] = $tor_num_trackers[1];

	    if($this->details['completed_chunks'] < $this->details['size_in_chunks'])
	    {
		    $this->details['percent'] = floor(($this->details['completed_chunks']/$this->details['size_in_chunks'])*100);
	    } else {
            $this->details['percent'] = 100;
	    }
    }
    private function changePriority($hash, $priority)
    {
    	if($hash != '' && $priority >= 0 && $priority <= 3)
    	{
    		$message = new xmlrpcmsg("d.set_priority", array(new xmlrpcval($hash, 'string'), new xmlrpcval($priority, 'int')));
			$result = $this->client->send($message);
			$this->addMessage($this->_str['info_pr']);
    	} else {
    		$this->addMessage($this->_str['err_pr']);
    	}
    }
}
?>