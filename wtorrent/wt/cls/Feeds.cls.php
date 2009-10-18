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
	private $feed;
	private $feed_title;
	private $feed_url;
	private $view_feed = false;

	const FEED_MAX_DAYS = 14;
	const FEED_MAX_ITEMS = 30;

	public function construct()
	{
		if(!$this->setClient())
		{
			return false;
		}
		$action = $this->getParam('action', 'view');

		switch ($action)
		{
		case 'download':
		case 'add':
		case 'erase':
		case 'changeDir':
		case 'viewFeed':
		case 'edit':
			$this->$action();
			break;
		}
		
		if($this->view_feed === false)
		{
			$this->view();
		}
	}
	private function viewFeed()
	{
		$id = $this->getParamInt('feed_id');
		if ($id == 0) {
			return;
		}
		$this->view_feed = $id;
		$feed = $this->_db->query(
			'SELECT url, title FROM feeds WHERE user = ? AND id = ?',
			$this->getIdUser(),
			$id
		);
		$this->feed = new SimplePie();
		$this->feed->set_feed_url($this->feed_url = $feed['url']);
		$this->feed->set_cache_location(DIR_TPL_COMPILE);
		$this->feed->init();
		$this->feed->handle_content_type();
		$this->feed_title = $feed['title'];
	}
	private function view()
	{	
		$feeds = $this->_db->queryAll(
			'SELECT id, url, title FROM feeds WHERE user = ? ORDER BY title, url',
			$this->getIdUser()
		);
		$this->info = array();
		foreach ($feeds as $feed) {
			$pie = new SimplePie();
			$pie->set_feed_url($feed['url']);
			$pie->set_cache_location(rtrim(DIR_TPL_COMPILE, '/'));
			$pie->init();
			$pie->handle_content_type();
			$items = $pie->get_items(0, self::FEED_MAX_ITEMS);
			$now = time();
			for ($i = count($items) - 1; $i != 0; --$i)
			{
				$time = $items[$i]->get_date('U');				
				if ($time && $now - $time > self::FEED_MAX_DAYS * 86400)
				{
					array_pop($items);
				}
			}
			$this->info[] = array(
				'title' => (empty($feed['title']) ? $pie->get_title() : $feed['title']),
				'id' => $feed['id'],
				'description' => $pie->get_description(),
				'news' => $items,
			);
		}
	}
	private function changeDir()
	{
		$dir = $this->getParam('dir');
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
	private function download()
	{
		$uri = $this->getParam('uri');
		if (empty($uri)) {
			return;
		}
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
		return $this->feed;
	}
	public function getFeedTitle() {
		return empty($this->feed_title) ? $this->feed->get_title() : $this->feed_title;
	}
	public function getFeedUrl() {
		return $this->feed_url;
	}
	public function getView()
	{
		return $this->view_feed;
	}
	public function getTitle($index)
	{
		return $this->info[$index]['title'];
	}
	private function add()
	{
		$url = $this->getParam('feed_url', '', $this->_post);
		$title = $this->getParam('feed_title', '', $this->_post);
		if (!empty($url))
	   	{
			$this->_db->modify(
				'INSERT INTO feeds (url, title, user) VALUES(?, ?, ?)',
				$url,
				$title,
				$this->getIdUser()
			);
			$this->addMessage($this->_str['info_add_feed']);
		}
	}

	private function erase()
   	{
		$id = $this->getParamInt('feed_id', 0);
		if ($id)
		{
			$this->_db->modify(
				'DELETE FROM feeds WHERE user = ? AND id = ?',
				$this->getIdUser(),
				$id
			);
			$this->addMessage($this->_str['info_erase_feed']);
		}
	}
	private function edit()
	{
		$this->addMessage($this->_str['not_implemented']);
	}
}
?>
