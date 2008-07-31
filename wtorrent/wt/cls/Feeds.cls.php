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
		/*if(isset($this->_request['stop'])) $this->stop($this->_request['stop']);
		if(isset($this->_request['erase'])) $this->erase($this->_request['erase']);
		if(isset($this->_request['ch_dw'])) $this->setDownLimit($this->_request['down_rate']);
		if(isset($this->_request['ch_up'])) $this->setUploadLimit($this->_request['up_rate']);*/	
		}
	}
	private function viewFeed($id)
	{
		$this->view_feed = $id;
		$sql = "SELECT url FROM feeds where user = " . $this->getIdUser() . " AND id = '$id';";
		$res = $this->_db->query( $sql );
		$result = $res->fetchAll();
		
		$this->feeds = new SimplePie();
		$this->feeds->set_feed_url($result[0]['url']);
		$this->feeds->set_cache_location(DIR_TPL_COMPILE);
		$this->feeds->init();
		$this->feeds->handle_content_type();
		
		/*$this->info['title'] = $this->feeds->get_title();
    	$this->info['description'] = $this->feeds->get_description();
    	$this->info['items'] = $this->feeds->get_items();*/
	}
	private function setFeeds()
	{	
		$sql = "SELECT id, url FROM feeds where user = " . $this->getIdUser() . ";";
		$res = $this->_db->query( $sql );
		$result = $res->fetchAll();
		$num_feeds = count($result);
		/*$sql = "select count(*) from feeds where user = " . $this->getIdUser() . ";";
		$num_feeds = 1 ;//count($this->feeds);*/
		for($i = 0; $i < $num_feeds; $i++)
		{
			$this->feeds[$i]['feed'] = new SimplePie();
			$this->feeds[$i]['feed']->set_feed_url($result[$i]['url']);
			$this->feeds[$i]['feed']->set_cache_location(rtrim(DIR_TPL_COMPILE, '/'));
			$this->feeds[$i]['feed']->init();
			$this->feeds[$i]['feed']->handle_content_type();
			$this->feeds[$i]['id'] = $result[$i]['id'];
		}
	}
	private function changeDir($dir)
	{
		$message = new xmlrpcmsg("set_directory", array(new xmlrpcval($dir , 'string')));
		$result = $this->client->send($message);
		if($result->errno == 0)
			$this->addMessage($this->_str['info_ch_dir']);
		else
			$this->addMessage($this->_str['err_ch_dir']);
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
		if($this->view_feed === false)
			return false;
		else
			return true;
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
   		/*foreach ($this->feeds[$i]->get_items() as $key => $item)
   		{
   			$this->info[$i]['items'][$key]['title'] = $item->get_title();
   			$this->info[$i]['items'][$key]['description'] = $item->get_description();
   			$this->info[$i]['items'][$key]['link'] = $item->get_link();
   			$this->info[$i]['items'][$key]['date'] = $item->get_date("U");
   		}*/
   	}
  }
  private function AddFeed($feed_url)
  {
   	$sql = "INSERT INTO feeds(url, user) VALUES('$feed_url' , '". $this->getIdUser() . "');";
   	$this->_db->query($sql);
   	$this->addMessage($this->_str['info_add_feed']);
  }
  private function DeleteFeed($id)
  {
   	$sql = "DELETE FROM feeds WHERE user = '". $this->getIdUser() . "' AND id = '$id';";
   	$this->_db->query($sql);
   	$this->addMessage($this->_str['info_erase_feed']);
  }
}
?>