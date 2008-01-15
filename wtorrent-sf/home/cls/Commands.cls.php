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
class Commands extends rtorrent
{
    /////////////////////////////////// C O N S T R U C T O R A S  Y  D E S T R U C T O R A ///////////////////////////////////

    public function construct()
	{
    	if(isset($this->_request['command']) && $this->_request['command'] != '' && isset($this->_request['param']) && $this->_request['param'] != '') 
    	{
    		if(!$this->setClient())
    			return false;
    			
    		switch($this->_request['command'])
    		{
    			case 'start':
    				$this->start($this->_request['param']);
    				break;
    			case 'stop':
    				$this->stop($this->_request['param']);
    				break;
    			case 'erase':
    				$this->erase($this->_request['param']);
    				break;
    			case 'set_down_limit':
    				$this->setDownLimit($this->_request['param']);
    				break;
    			case 'set_up_limit':
    				$this->setUploadLimit($this->_request['param']);
    				break;
    			case 'files':
    				$this->changeFiles($this->_request['param'], $this->_request['param1'], $this->_request['param2']);
    				break;
    			case 'info':
    				$this->changePriority($this->_request['param'], $this->_request['param1']);
    				break;
    			case 'trackers':
    				$this->changeTrackers($this->_request['param'], $this->_request['param1'], $this->_request['param2']);
    				break;
    			default:
    				$this->addMessage($this->_str['command_error']);
    				break;
    				
    		}
    	} else {
    		$this->addMessage($this->_str['command_error']);
    	}
	}
	private function stop($hashes)
    {
    	if(is_array($hashes))
    		$hashes = array_keys($hashes);
    	else 
    		$hashes = array($hashes);
    	
    	foreach($hashes as $hash)
    	{
    		$message = new xmlrpcmsg("d.stop", array(new xmlrpcval($hash, 'string')));
			$result = $this->client->send($message);
    	}
    	
    	$this->addMessage($this->_str['info_tor_stop']);
    }
    private function start($hashes)
    {
    	if(is_array($hashes))
    		$hashes = array_keys($hashes);
    	else 
    		$hashes = array($hashes);
    	
    	foreach($hashes as $hash)
    	{
    		$message = new xmlrpcmsg("d.start", array(new xmlrpcval($hash, 'string')));
			$result = $this->client->send($message);
    	}
    	
    	$this->addMessage($this->_str['info_tor_start']);
    }
    private function erase($hashes)
    {
    	if(is_array($hashes))
    		$hashes = array_keys($hashes);
    	else 
    		$hashes = array($hashes);
    	
    	foreach($hashes as $hash)
    	{
    		$message = new xmlrpcmsg("d.erase", array(new xmlrpcval($hash, 'string')));
			$result = $this->client->send($message);
			$sql = "delete from torrents where hash = '" . $hash . "'";
			$this->_db->query($sql);
    	}
    	
    	$this->addMessage($this->_str['info_tor_erase']);
    }
    private function setDownLimit($limit)
    {
    	$message = new xmlrpcmsg("set_download_rate", array(new xmlrpcval($limit*1024, 'int')));
		$result = $this->client->send($message);
		//print_r($result);
		if($result->errno == 0)
			$this->addMessage($this->_str['info_down_limit']);
		else
			$this->addMessage($this->_str['err_down_limit']);	
    }
    private function setUploadLimit($limit)
    {
    	$message = new xmlrpcmsg("set_upload_rate", array(new xmlrpcval($limit*1024, 'int')));
		$result = $this->client->send($message);
		if($result->errno == 0)
			$this->addMessage($this->_str['info_up_limit']);
		else
			$this->addMessage($this->_str['err_up_limit']);	
    }
    private function changeFiles($hash, $priority, $files)
    {
    	$files = explode('~', $files);
    	
    	foreach($files as $param)
			$array_post[] = new xmlrpcmsg('f.set_priority', array(new xmlrpcval($hash, 'string'), new xmlrpcval($param, 'int'), new xmlrpcval($priority, 'int')));
		
		$result = $this->client->multicall($array_post);
		
		if($result->errno == 0)
			$this->addMessage($this->_str['info_ch_files']);
		else
			$this->addMessage($this->_str['err_ch_files']);
			 
		$mesage = new xmlrpcmsg('d.update_priorities', array(new xmlrpcval($hash, 'string')));
		$this->client->send($mesage);
    }
    private function changePriority($hash, $priority)
    {
    	$message = new xmlrpcmsg("d.set_priority", array(new xmlrpcval($hash, 'string'), new xmlrpcval($priority, 'int')));
		$result = $this->client->send($message);
		if($result->errno == 0)
			$this->addMessage($this->_str['info_pr']);
    	else 
    		$this->addMessage($this->_str['err_pr']);
    }
    private function changeTrackers($hash, $enabled, $t_index)
    {
    	$t_index = explode('~', $t_index);
    	//print_r($f_index);
    	
    	foreach($t_index as $param)
			$array_post[] = new xmlrpcmsg('t.set_enabled', array(new xmlrpcval($hash, 'string'), new xmlrpcval($param, 'int'), new xmlrpcval($enabled, 'int')));
		
		//print_r($array_post);	
		$responses = $this->client->multicall($array_post);
		$this->addMessage($this->_str['info_ch_trackers']);
    }
}
?>