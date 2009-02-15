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

Modified version of class done by David Marco Martinez
*/
class rtorrent extends Web
{
	// Units for getCorrectUnits
	// not the "correct" units, however this is what users are used to.
	private static $UNITS = array(array(0, 'b'), array(1, 'kb'), array(2, 'MB'), array(2, 'GB'), array(3,'TB'), array(3, 'PT'));

	// Sorting stuff (there must be corresponding compare$name$order functions)
	// Possible sort orders
	protected static $SORT_ORDERS = array('asc', 'desc');
	// Possible sort keys
	protected  static $SORT_KEYS = array('name', 'dl', 'up', 'done', 'size', 'percent', 'ratio');


	protected $client;
	protected $multicall;
	protected $torrents;
	protected $data;
	protected $rtorrent_view;
	
	private $menu = array(
						'Main' => 'ListT',
						'Add Torrent' => 'AddT',
						'Feeds' => 'Feeds',
						'Cookie' => 'Cookie');
	private $menu_admin = array('Main' => 'ListT',
						'Add Torrent' => 'AddT',
						'Feeds' => 'Feeds',
						'Cookie' => 'Cookie',
						'Admin' => 'Admin');
	private $submenu	= array('General' => 'General&tpl=details',
						'Files' => 'Files&tpl=details',
						'Tracker' => 'Tracker&tpl=details');
	protected $admin;

	public function __construct( )
	{
		parent::__construct( );
		if(isset($this->_request['logout'])) $this->logout();
		if(isset($this->_request['user_login'])) $this->login($this->_request['userf'], $this->_request['passwdf']);
	}
	private function checkError($result)
	{
		if($result->errno == '0')
			return true;
		else
			return false;
	}
	protected function construct( ){}

	public function isRegistered( )
	{
		if($this->admin && $this->_session->admin)
		{
			return !is_null( $this->_session->id_user );
		} elseif(!$this->admin) {
			return !is_null( $this->_session->id_user );
		} else {
			return false;
		}
	}
	public function getUser( )
	{
		return $this->_session->user;
	}
	public function getIdUser( )
	{
		return $this->_session->id_user;
	}
	public function getMenu()
	{
		if($this->_session->admin === true)
			$return = $this->menu_admin;
		else 
			$return = $this->menu;
			
		return $return;
	}
	public function getDetailsMenu()
	{
		return $this->submenu;
	}
	public function getWidth($total, $menu_items)
	{
		return floor($total/(count($menu_items)+1)) - 1;
	}
	public function login( $user, $password )
	{	
		$record = $this->_db->query(
			'SELECT id, admin FROM tor_passwd WHERE user = ? AND passwd = ?',
			$user,
			md5($password)
		);
		if($record !== false)
		{
			$this->_session->id_user = $record['id'];
			$this->_session->admin = !!$record['admin'];
			$this->_session->user = $user;
			return true;
		}
		return false;
	}
	public function isAdmin()
	{
		return $this->_session->admin;
	}
	public function logout()
	{
		if($this->isRegistered())
		{
			$this->_session = null;
			$this->addMessage($this->_str['info_logout']);
		}
	}
	private function getPrivate()
	{
		$rv = array();
		foreach ($this->_db->queryAll('SELECT hash, user FROM torrents') as $torrent)
		{
			$rv[$torrent['hash']] = $torrent['user']; 
		}
		return $rv;
	}
	public function setClient()
	{
		$this->client = new xmlrpc_client(RT_DIR, RT_HOST, RT_PORT);
		$this->multicall = new multicall($this->client, $this->data);

		if(RT_AUTH)
			$this->client->setCredentials(RT_USER, RT_PASSWD);
    
		$this->client->return_type = 'phpvals';
    $this->client->no_multicall = NO_MULTICALL;
    	
    $message = new xmlrpcmsg("system.pid");
		$result = $this->client->send($message);

		if($result->errno != 0)
		{
			$this->addMessage($this->_str['err_conn']);
			return false;
		} else {
			$this->setTorrents();
			return true;
		}
	}
	/* Initializes torrent objects */
	protected function setTorrents() {
		$message = new xmlrpcmsg("d.multicall", array(new xmlrpcval($this->rtorrent_view, 'string'),new xmlrpcval('d.get_hash=', 'string')));
		$result = $this->client->send($message);
		if(is_array($result->val))
		{
			foreach($result->val as $hash)
			{
				$this->torrents[$hash[0]] = new torrent($hash[0], $this->client, $this->multicall, $this->data[$hash[0]]);
			}
			/* Mark torrents as public/private and assign owners */
			$private_torrents = $this->getPrivate();
			$hashes = $this->getHashes();
			if(!empty($hashes))
			{
				foreach($hashes as $hash)
				{
					if(array_key_exists($hash, $private_torrents))
					{
						$this->data[$hash]['private'] = true;
						$this->data[$hash]['owner'] = $private_torrents[$hash];
					} else {
						$this->data[$hash]['private'] = false;
						$this->data[$hash]['owner'] = 0;
					}
				}
			}
		}
	}
	protected function sortTorrentsBy($key, $order)
	{
		if (!in_array($order, self::$SORT_ORDERS))
		{
			$order = self::$SORT_ORDERS[0];
		}
		if  (!in_array($key, self::$SORT_KEYS))
		{
			$key = self::$SORT_KEYS[0];
		}
		uasort(
			$this->torrents,
			array(
				self,
				sprintf(
					'compare%s%s', 
					ucfirst($key),
					ucfirst($order)
				)
			)
		);
	}
	protected function getHashes()
	{
		if(!empty($this->torrents))
		{
			$return = array_keys($this->torrents);
		}
		return $return;
	}
	protected function erase_db($hash)
	{
		$this->_db->modify('DELETE FROM torrents WHERE hash = ?', $hash);
	}
	protected function setPerm()
	{
		$this->admin = false;
	}
	/* xmlrpc rtorrent admin functions (not torrent related ) */
	protected function set_download_rate($limit)
	{
		$message = new xmlrpcmsg("set_download_rate", array(new xmlrpcval($limit, 'int')));
		$result = $this->client->send($message);
		
		if($this->checkError($result))
		{
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	protected function set_upload_rate($limit)
	{
		$message = new xmlrpcmsg("set_download_rate", array(new xmlrpcval($limit, 'int')));
		$result = $this->client->send($message);
		
		if($this->checkError($result))
		{
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	/* User Interface methods */
	private function get_info_rtorrent($method, $multicall, $update)
	{
		if($update || !isset($this->data[$method]))
		{
			$message = new xmlrpcmsg($method);
		}
		
		if(isset($message))
		{
			if($multicall === true)
			{
				$return = $this->multicall->add($message, $this->hash);
			}
			else
			{
				$result = $this->client->send($message);
				$return = $this->checkError($result);
				if($return)
				{
					$this->data[$method] = $result->val;
					$return = $result->val;
				}
			}
		} else {
			$return = $this->data[$method];
		}
		return $return;
	}
	public function view_list($multicall = false, $update = false)
	{
		return $this->get_info_rtorrent('view_list', $multicall, $update);
	}
	/* Upload/Download rate functions */
	public function get_down_rate($multicall = false, $update = false)
	{
		return $this->get_info_rtorrent('get_down_rate', $multicall, $update);
	}
	public function get_up_rate($multicall = false, $update = false)
	{
		return $this->get_info_rtorrent('get_up_rate', $multicall, $update);
	}
	/* rTorrent info functions */
	public function getDownload() 
	{
		return round($this->get_down_rate()/1024,2) . 'KB/s';
	}
	public function getUpload() 
	{
		return round($this->get_up_rate()/1024,2) . 'KB/s';
	}
	/* Disk related functions */
	public function getFreeSpace()
	{
		 return $this->getCorrectUnits(disk_free_space(DIR_DOWNLOAD));
	}
	public function getUsedSpace()
	{
		return $this->getCorrectUnits(disk_total_space(DIR_DOWNLOAD) - disk_free_space(DIR_DOWNLOAD));
	}
	public function getTotalSpace()
	{
		return $this->getCorrectUnits(disk_total_space(DIR_DOWNLOAD));
	}
	public function getUsedPercent()
	{
		return round((disk_total_space(DIR_DOWNLOAD) - disk_free_space(DIR_DOWNLOAD))/disk_total_space(DIR_DOWNLOAD)*100,0);
	}
	public function getCorrectUnits($size)
 	{
		$i;
		$e = sizeof(self::$UNITS);
		for ($i = 0; $i < $e && $size > 900; ++$i)
		{
			$size = $size / 1024;
		}
		return sprintf('%.'.(self::$UNITS[$i][0]).'f %s', $size, self::$UNITS[$i][1]);
	}

	// Sort functions
	static function compareNameAsc($a, $b)
	{
		return strnatcasecmp($a->get_name(), $b->get_name());
	}
	static function compareNameDesc($a, $b)
	{
		return strnatcasecmp($b->get_name(), $a->get_name());
	}
	static function compareDlAsc($a, $b)
	{
		return $a->get_down_rate() - $b->get_down_rate();
	}
	static function compareDlDesc($a, $b)
	{
		return $b->get_down_rate() - $a->get_down_rate();
	}
	static function compareUpAsc($a, $b)
	{
		return $a->get_up_rate() - $b->get_up_rate();
	}
	static function compareUpDesc($a, $b)
	{
		return $b->get_up_rate() - $a->get_up_rate();
	}
	static function compareDoneAsc($a, $b)
	{
		$an = $a->get_completed_chunks() * $a->get_chunk_size();
		$bn = $b->get_completed_chunks() * $b->get_chunk_size();

		return $an - $bn;
	}
	static function compareDoneDesc($a, $b)
	{
		$an = $a->get_completed_chunks() * $a->get_chunk_size();
		$bn = $b->get_completed_chunks() * $b->get_chunk_size();

		return $bn - $an;
	}
	static function compareSizeAsc($a, $b)
	{
		// possible int overflows here.
		// hence the complicated calculation here ;)
		$r = floatval($a->get_chunk_size()) / floatval($b->get_chunk_size());
		$an = $a->get_size_chunks() * $r;
		$bn = $b->get_size_chunks();

		// need to care that the result may be within [-1, 1]
		$rv = ($an - $bn);
		return $rv == 0 ? 0 : ($rv < 0 ? -1 : 1);
	}
	static function compareSizeDesc($a, $b)
	{
		$r = floatval($a->get_chunk_size()) / floatval($b->get_chunk_size());
		$an = $a->get_size_chunks() * $r;
		$bn = $b->get_size_chunks();

		$rv = ($bn - $an);
		return $rv == 0 ? 0 : ($rv < 0 ? -1 : 1);
	}
	static function comparePercentAsc($a, $b)
	{
		$an = $a->get_completed_chunks() / $a->get_size_chunks();
		$bn = $b->get_completed_chunks() / $b->get_size_chunks();
		// Operating on raw percentages, hence scale by 100 or else we will be within (-1,1) and
		// sort() doesn't like values |x| < 1
		return ($an - $bn) * 100;
	}
	static function comparePercentDesc($a, $b)
	{
		$an = $a->get_completed_chunks() / $a->get_size_chunks();
		$bn = $b->get_completed_chunks() / $b->get_size_chunks();
		return ($bn - $an) * 100;
	}
	static function compareRatioAsc($a, $b)
	{
		return $a->get_ratio() - $b->get_ratio();
	}
	static function compareRatioDesc($a, $b)
	{
		return $b->get_ratio() - $a->get_ratio();
	}
}
?>
