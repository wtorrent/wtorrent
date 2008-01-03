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
class AddT extends rtorrent
{
    /////////////////////////////////// C O N S T R U C T O R A S  Y  D E S T R U C T O R A ///////////////////////////////////

    public function construct()
	{
    	if(isset($this->_request['upload_torrent'])) 
    	{
    		if(!$this->setClient())
    			return false;
    			
    		$this->uploadTorrent($this->_files['uploadedfile'], $this->_request['download_dir'], $this->_request['start_now'], $this->_request['private']);
    	}
	}
	private function uploadTorrent($fileU, $dir, $start_now, $private)
	{
		$uploadfile = DIR_EXEC . DIR_TORRENTS . basename($fileU['name']);
		if(!is_writable(DIR_EXEC . DIR_TORRENTS))
		{
			$this->addMessage($this->_str['err_add_dir']);
			return false;
		}
		if(file_exists($uploadfile))
		{
			$this->addMessage($this->_str['err_add_file']);
			return false;
		}
		
		$res[] = move_uploaded_file($fileU['tmp_name'], $uploadfile);
		chmod( $uploadfile, PERM_TORRENTS);
		
		$message = new xmlrpcmsg("set_directory", array(new xmlrpcval($dir , 'string')));
		$result1 = $this->client->send($message);
		
		if($start_now == 'on')
			$method = 'load_start';
		else
			$method = 'load';
		if($private == 'on')
		{
			$torrent = new BDECODE($uploadfile);
			$bencode = new BEncodeLib();
			$hash = strtoupper(bin2hex(sha1($bencode->bencode($torrent->result['info']), true)));
			$sql =  "insert into torrents values('" . $hash . "', '" . $this->getIdUser() . "', '1');";
			$this->_db->query($sql);
		}
			// sha.new( bencode ( bdecode( open('file.torrent', 'rb').read() )['info'] ) ).hexdigest()
			
		$message = new xmlrpcmsg($method, array(new xmlrpcval($uploadfile , 'string')));
		$result2 = $this->client->send($message);
		
		/*print_r($result1);
		print_r($result2);*/
		
		if(($result1->errno == 0) && ($result2->errno == 0) && ($res[0] !== false))
			$this->addMessage($this->_str['info_add_torrent']);
		else
		{
			$this->addMessage($this->_str['err_add_torrent']);
			@unlink($uploadfile);
		}
	}
}
?>