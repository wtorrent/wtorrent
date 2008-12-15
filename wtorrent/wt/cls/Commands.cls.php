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
				case 'close':
					$this->close($this->_request['param']);
					break;
				case 'erase':
					$this->erase($this->_request['param']);
					break;
				case 'chash':
					$this->chash($this->_request['param']);
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
		$hashes = explode('~', $hashes);
		if(!is_array($hashes))
			$hashes = array($hashes);

		foreach($hashes as $hash)
		{
			$this->torrents[$hash]->stop(true);
		}
		$this->multicall->call();

		$this->addMessage($this->_str['info_tor_stop']);
	}
	private function close($hashes)
	{
		$hashes = explode('~', $hashes);
		if(!is_array($hashes))
			$hashes = array($hashes);

		foreach($hashes as $hash)
		{
			$this->torrents[$hash]->stop(true);
			$this->torrents[$hash]->close(true);
		}
		$this->multicall->call();

		$this->addMessage($this->_str['info_tor_close']);
	}
	private function chash($hashes)
	{
		$hashes = explode('~', $hashes);
   	if(!is_array($hashes))
   		$hashes = array($hashes);
   	
   	foreach($hashes as $hash)
   	{
   		$this->torrents[$hash]->check_hash(true);
   	}
		$this->multicall->call();
   	
   	$this->addMessage($this->_str['info_tor_chash']);
	}
	private function start($hashes)
	{
 		$hashes = explode('~', $hashes);
   	if(!is_array($hashes))
   		$hashes = array($hashes);
   	
   	foreach($hashes as $hash)
   	{
   		$this->torrents[$hash]->start(true);
   	}
		$this->multicall->call();
   	
   	$this->addMessage($this->_str['info_tor_start']);
	}
  private function erase($hashes)
	{
  	$hashes = explode('~', $hashes);
    if(!is_array($hashes))
    	$hashes = array($hashes);
    	
    foreach($hashes as $hash)
    {
    	$this->torrents[$hash]->erase();
			$this->erase_db($hash);
    }
    	
    $this->addMessage($this->_str['info_tor_erase']);
	}
	private function setDownLimit($limit)
	{
  	$result = $this->set_download_rate($limit*1024);
		if($result)
			$this->addMessage($this->_str['info_down_limit']);
		else
			$this->addMessage($this->_str['err_down_limit']);	
	}
  private function setUploadLimit($limit)
	{
  	$result = $this->set_upload_rate($limit*1024);
		if($result)
			$this->addMessage($this->_str['info_down_limit']);
		else
			$this->addMessage($this->_str['err_down_limit']);
	}
  private function changeFiles($hash, $priority, $files)
	{
		$files = explode('~', $files);
    	
		foreach($files as $param)
			$this->torrents[$hash]->f_set_priority($param, $priority, true);
		
		$result = $this->multicall->call();
		
		if($result)
			$this->addMessage($this->_str['info_ch_files']);
		else
			$this->addMessage($this->_str['err_ch_files']);
			 
		$this->torrents[$hash]->update_priorities();
	}
  private function changePriority($hash, $priority)
	{
		$result = $this->torrents[$hash]->d_set_priority($priority);
		
		if($result)
			$this->addMessage($this->_str['info_pr']);
    else 
			$this->addMessage($this->_str['err_pr']);
	}
  private function changeTrackers($hash, $enabled, $trackers)
	{
		$trackers = explode('~', $trackers);
    	
		foreach($trackers as $param)
			$this->torrents[$hash]->t_set_enabled($param, $enabled, true);
		
		$this->multicall->call();
		
		$this->addMessage($this->_str['info_ch_trackers']);
	}
}
?>
