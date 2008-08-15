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
	private $hash;

 	public function construct()
	{
		$this->hash = $this->_request['hash'];
		
		if(!$this->setClient())
			return false;
			
		$array_f = array('f.get_path', 'f.get_completed_chunks', 'f.get_size_chunks','f.get_priority');
		$this->multicall->f_multicall($this->hash, $array_f);
	}
	public function getHash()
	{
		return $this->hash;
	}
	public function getFiles()
	{
		$num = $this->torrents[$this->hash]->get_size_files();
		for($i = 0; $i < $num; $i++)
  	{		
  		$files[$i]['name'] = $this->torrents[$this->hash]->f_get_path($i);
  		$files[$i]['size_in_chunks'] = $this->torrents[$this->hash]->f_get_size_chunks($i);
  		$files[$i]['completed_chunks'] = $this->torrents[$this->hash]->f_get_completed_chunks($i);
			$files[$i]['priority'] = $this->torrents[$this->hash]->f_get_priority($i);
			if($files[$i]['size_in_chunks'] > 0)
				$files[$i]['percent'] = floor(($files[$i]['completed_chunks']/$files[$i]['size_in_chunks'])*100);
			else
				$files[$i]['percent'] = 100;
			$files[$i]['size'] = $files[$i]['size_in_chunks'] * $this->torrents[$this->hash]->get_chunk_size();
  		$files[$i]['size_done'] = $files[$i]['completed_chunks'] * $this->torrents[$this->hash]->get_chunk_size();
  	}
		return $files;
	}
	public function getSize($key)
	{
		return $this->getCorrectUnits($this->torrents[$this->hash]->f_get_size_chunks($key) * $this->torrents[$this->hash]->get_chunk_size());
	}
	public function getDone($key)
	{
		return $this->getCorrectUnits($this->torrents[$this->hash]->f_get_completed_chunks($key) * $this->torrents[$this->hash]->get_chunk_size());
	}
	public function getPriorityStr($key)
	{
		switch($this->torrents[$this->hash]->f_get_priority($key))
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
}
?>