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
class Feeds extends rtorrent
{
	private $info = array();
	private $feeds = array();
	private $view_feed = false;

	public function construct()
	{
		if(!$this->setClient())
			return false;
		
		if(isset($this->_request['download'])) $this->Download($this->_request['download']);
		if(isset($this->_request['feed_add'])) $this->AddFeed($this->_request['feed_url']);
		if(isset($this->_request['erase'])) $this->DeleteFeed($this->_request['erase']);
		if(isset($this->_request['ch_dir'])) $this->changeDir($this->_request['down_dir']);
		if(isset($this->_request['view'])) $this->viewFeed($this->_request['view']);
		
		if($this->view_feed === false)
		{
			$this->setFeeds();
			$this->fetchFeeds();
		}
	}
	private function viewFeed($id)
	{
		$this->view_feed = $id;
		$url = $this->_db->queryColumn(
			'SELECT url FROM feeds WHERE user = ? AND id = ?',
			$this->getIdUser(),
			$id
		);
		$this->feeds = new SimplePie();
		$this->feeds->set_feed_url($url);
		$this->feeds->set_cache_location(DIR_TPL_COMPILE);
		$this->feeds->init();
		$this->feeds->handle_content_type();
	}
	private function setFeeds()
	{	
		$feeds = $this->_db->queryAll(
			'SELECT id, url FROM feeds WHERE user = ?',
			$this->getIdUser()
		);
		for ($i = 0, $e = sizeof($feeds); $i < $e; ++$i)
		{
			$feed = $feeds[$i];
			$pie = new SimplePie();
			$pie->set_feed_url($feed['url']);
			$pie->set_cache_location(rtrim(DIR_TPL_COMPILE, '/'));
			$pie->init();
			$pie->handle_content_type();
			$this->feeds[] = array(
				'feed' => $pie,
				'id' => $feed['id']
			);
		}
	}
	private function changeDir($dir)
	{
		$message = new xmlrpcmsg("set_directory", array(new xmlrpcval($dir , 'string')));
		$result = $this->client->send($message);
		if($result->errno == 0)
		{
			$this->addMessage($this->_str['info_ch_dir']);
		}
		else
		{
			$this->addMessage($this->_str['err_ch_dir']);
		}
	}
	private function Download($uri)
	{
		$message = new xmlrpcmsg("load_start", array(new xmlrpcval($uri , 'string')));
		$result = $this->client->send($message);
		$this->addMessage($this->_str['down_started']);
	}
	public function getDownDir()
	{
		$message = new xmlrpcmsg("get_directory");
		$result = $this->client->send($message);
		return $result->val;
	}
	public function isView()
	{
		return $this->view_feed;
	}
	public function getFeeds()
	{
		return $this->info;
	}
	public function getFeed()
	{
		return $this->feeds;
	}
	public function getView()
	{
		return $this->view_feed;
	}
	public function getTitle($index)
	{
		return $this->info[$index]['title'];
	}
	private function fetchFeeds()
 	{
	 	$num_feeds = count($this->feeds);
	 	for($i = 0; $i < $num_feeds;$i++)
	 	{
	 		$this->info[$i]['title'] = $this->feeds[$i]['feed']->get_title();
	 		$this->info[$i]['description'] = $this->feeds[$i]['feed']->get_description();
	 		$this->info[$i]['id'] = $this->feeds[$i]['id'];
	 	}
	}
	private function AddFeed($feed_url)
	{
		$this->_db->modify(
			'INSERT INTO feeds (url, user) VALUES (?, ?)',
			$feed_url,
			$this->getIdUser()
		);
	 	$this->addMessage($this->_str['info_add_feed']);
	}
	private function DeleteFeed($id)
	{
		$this->_db->modify(
			'DELETE FROM feeds WHERE user = ? AND id = ?',
			$this->getIdUser(),
			$id
		);
	 	$this->addMessage($this->_str['info_erase_feed']);
	}
}
?>
