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
class Files extends rtorrent
{
	private  $files;
	private  $hash;
	private  $info_files = array('', '',
								'f.get_path=', 
    							'f.get_completed_chunks=', 
    							'f.get_size_chunks=',
    							'f.get_priority=');
    private $position = 0;

    public function construct()
	{
		$this->hash = $this->_request['hash'];
		
		if(!$this->setClient())
			return false;
			
		if(isset($this->_request['ch_pr']) && count($this->_request['files']) > 0) $this->changePriorities($this->_request['files'], $this->_request['priority'], $this->hash);
		$this->getTorrents($this->_request['hash']);
	}
	
	
	public function getHash()
	{
		return $this->hash;
	}
	public function getFiles()
	{
		return $this->files;
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
	public function getSize($key)
	{
		return $this->getCorrectUnits($this->files[$key]['size']);
	}
	public function getDone($key)
	{
		return $this->getCorrectUnits($this->files[$key]['size_done']);
	}
	public function getUp()
	{
		return $this->getCorrectUnits($this->details['bytes_up']);
	}
	public function getPriorityStr($key)
	{
		switch($this->files[$key]['priority'])
		{
			case 0:
				return $this->_str['file_off'];
				break;
			case 1:
				return $this->_str['file_normal'];
				break;
			case 2:
				return $this->_str['file_high'];
				break;
		}
	}
	public function getPriorities()
	{
		return array('0' => $this->_str['file_off'], '1' => $this->_str['file_normal'], '2' => $this->_str['file_high']);
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
    	
    	$this->info_files[0] = $hash;
    	
		foreach($this->info_files as $param)
			$array_post[] = new xmlrpcval($param, 'string');
		
		// Get chunk size
		$message = new xmlrpcmsg("d.get_chunk_size", array(new xmlrpcval($hash, 'string')));
		$result = $this->client->send($message);
		$chunk_size = $result->val;
		// Get file info
    	$message = new xmlrpcmsg("f.multicall", $array_post);
		$result = $this->client->send($message);
    	//print_r($result);
    	foreach($result->val as $key => $file)
    	{
    		if(SCRAMBLE === true)
    			$file[0] = $this->scramble($file[0]);
    			
    		$this->files[$key]['name'] = $file[0];
    		$this->files[$key]['size_in_chunks'] = $file[2];
    		$this->files[$key]['completed_chunks'] = $file[1];
    		$this->files[$key]['priority'] = $file[3];
    		$this->files[$key]['percent'] = floor(($this->files[$key]['completed_chunks']/$this->files[$key]['size_in_chunks'])*100);
    		$this->files[$key]['size'] = $this->files[$key]['size_in_chunks'] * $chunk_size;
    		$this->files[$key]['size_done'] = $this->files[$key]['completed_chunks'] * $chunk_size;
    	}
    	//print_r($this->files);
    }
    private function changePriorities($files, $priorities, $hash)
    {
    	$f_index = array_keys($files);
    	//print_r($f_index);
    	
    	foreach($f_index as $param)
			$array_post[] = new xmlrpcmsg('f.set_priority', array(new xmlrpcval($hash, 'string'), new xmlrpcval($param, 'int'), new xmlrpcval($priorities, 'int')));
		
		//print_r($array_post);	
		$responses = $this->client->multicall($array_post);
		
		$mesage = new xmlrpcmsg('d.update_priorities', array(new xmlrpcval($hash, 'string')));
		$this->client->send($mesage);
    }
}
?>