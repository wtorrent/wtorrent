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
	public function construct()
	{
		if(!$this->setClient())
		{
			return false;
		}

		if (!empty($this->_files['uploadedfile']) && !$this->_files['uploadedfile']['error'])
		{
			$this->uploadTorrent(
				$this->_files['uploadedfile'],
				$this->_request['download_dir'],
				$this->_request['start_now'],
				$this->_request['private']
		   	);
		}
		else if(!empty($this->_request['torrenturl']))
	   	{ 
			$this->addRemoteTorrent(
				$this->_request['torrenturl'],
				$this->_request['download_dir'],
				$this->_request['start_now'],
				$this->_request['private']
		   	);
		}	 
	}
	// Add remote torrent
	private function addRemoteTorrent( $url, $dir, $start_now, $private ) 
	{
		// Get Dir if user can only download to a certain directory
		if($this->getForceDir() == 1)
		{
			$dir = $this->getDir();
		}

		// Parsing url
		$purl = parse_url($url);
		$uploadfile = DIR_EXEC . DIR_TORRENTS . sha1( $url ) . md5($url) . ".torrent";
		// Get sha1/md5 for avoid filename problems & Multiple torrents
		if (file_exists($uploadfile))
		{ 
			$this->addMessage($this->_str['err_add_file']);
			return false;
		}
		$fh = fopen($uploadfile, 'w');
		// Open a filehandle and check for curl function in php
		if (!function_exists("curl_init"))
		{
			$this->addMessage( $this->_str['no_curl_function'] );
			return;
		}
		$ua = curl_init();
		$cookie = $this->getCookie( $url );
		if (!empty($cookie))
		{
			curl_setopt($ua, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($ua, CURLOPT_PORT,				$purl["port"] );
		curl_setopt($ua, CURLOPT_URL,				$url);
		curl_setopt($ua, CURLOPT_VERBOSE,			FALSE);
		curl_setopt($ua, CURLOPT_HEADER,			FALSE);
		// Dont put the header into the file
		curl_setopt($ua, CURLOPT_USERAGENT,			"Mozilla/5.0 (U; en-US; rv) Gecko Firefox (compatible wtorrent)");
		// Avoid problems with user agent sniffing
		curl_setopt($ua, CURLOPT_RETURNTRANSFER,	TRUE);
		curl_setopt($ua, CURLOPT_SSL_VERIFYHOST, 	FALSE);
		curl_setopt($ua, CURLOPT_SSL_VERIFYPEER, 	FALSE);
		// Avoid ssl problems
		curl_setopt($ua, CURLOPT_FOLLOWLOCATION, 	TRUE);
		// Follow the location
		curl_setopt($ua, CURLOPT_AUTOREFERER,		TRUE);
		curl_setopt($ua, CURLOPT_REFERER,			$url);
		// Avoid referrer problems
		curl_setopt($ua, CURLOPT_FILE,				 $fh);
		$file = curl_exec( $ua );
		// Execute the query
		curl_close($ua);
		fclose($fh);
		chmod( $uploadfile, PERM_TORRENTS);
		// Setting up the permissions
		$torrent = new BDECODE($uploadfile);
		// Try to load the torrent, and check is it valid or not
		if ($torrent->result['error'])
		{
			$this->addMessage($torrent->result['error']);
			@unlink($uploadfile);
			return false;
		}
		$message = new xmlrpcmsg("set_directory", array(new xmlrpcval($dir , 'string')));
		$result1 = $this->client->send($message);
		if($start_now == 'on')
		{
			$method = 'load_start';
		}
		else
		{
			$method = 'load';
		}
		if ($private == 'on')
		{ 
			$bencode = new BEncodeLib();
			$hash = strtoupper(bin2hex(sha1($bencode->bencode($torrent->result['info']), true)));
			$this->_db->modify('INSERT INTO torrents VALUES(?, ?, 1)', $hash, $this->getIdUser());
		}

		$message = new xmlrpcmsg($method, array(new xmlrpcval($uploadfile , 'string')));
		$result2 = $this->client->send($message);


		if (($result1->errno == 0) && ($result2->errno == 0) && ($res[0] !== false))
		{
			$this->addMessage($this->_str['info_add_torrent']);
		}
		else
		{ 
			$this->addMessage($this->_str['err_add_torrent']);
			@unlink($uploadfile);
		}
		$message = new xmlrpcmsg("set_directory", array(new xmlrpcval(DIR_DOWNLOAD, 'string')));
		$this->client->send($message);
	}
	private function getCookie($url) 
	{
		// Getting cookie depends on hostname
		$purl = parse_url( $url );
		return implode(
			';',
			$this->_db->queryColumnAll(
				'SELECT value FROM cookie WHERE userid = ? AND hostname LIKE ?',
				$this->getIdUser(),
				"%{$purl['host']}%"
			)
		);
	} 
	public function getDir()
	{
		return $this->_db->queryColumn('SELECT dir FROM tor_passwd WHERE id = ?', $this->getIdUser());
	}
	public function getForceDir()
	{
		return $this->_db->queryColumn('SELECT force_dir FROM tor_passwd WHERE id = ?', $this->getIdUser());
	}
	private function uploadTorrent($fileU, $dir, $start_now, $private)
	{
		if ($this->getForceDir() == 1)
		{
			$dir = $this->getDir();
		}
		$uploadfile = DIR_EXEC . DIR_TORRENTS . time() . basename($fileU['name']);
		if (!is_writable(DIR_EXEC . DIR_TORRENTS))
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
		{
			$method = 'load_start';
		}
		else
		{
			$method = 'load';
		}
		if($private == 'on')
		{
			$torrent = new BDECODE($uploadfile);
			$bencode = new BEncodeLib();
			$hash = strtoupper(bin2hex(sha1($bencode->bencode($torrent->result['info']), true)));
			$this->_db->modify('INSERT INTO torrents VALUES(?, ?, 1)', $hash, $this->getIdUser());
		}

		$message = new xmlrpcmsg($method, array(new xmlrpcval($uploadfile , 'string')));
		$result2 = $this->client->send($message);

		if(($result1->errno == 0) && ($result2->errno == 0) && ($res[0] !== false))
			$this->addMessage($this->_str['info_add_torrent']);
		else
		{
			$this->addMessage($this->_str['err_add_torrent']);
			@unlink($uploadfile);
		}
		$message = new xmlrpcmsg("set_directory", array(new xmlrpcval(DIR_DOWNLOAD, 'string')));
		$result1 = $this->client->send($message);
	}
}
?>
