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
	/*private  $torrents;
    private  $info_dowload = array('default', 
    							'd.get_hash=', 
    							'd.get_name=',
								'd.get_down_rate=',
								'd.get_up_rate=',
								'd.get_chunk_size=',
								'd.get_completed_chunks=',
								'd.get_size_chunks=',
								'd.get_state=',
								'd.get_peers_accounted=',
								'd.get_peers_complete=',
								'd.is_hash_checking=',
								'd.get_ratio=',
								'd.get_tracker_size=');
	private $info_tracker = array('',
								"",
								't.get_scrape_complete=',
								't.get_scrape_incomplete=');*/
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
			$this->feeds[$i]['feed']->set_cache_location(DIR_TPL_COMPILE);
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
	public function getUpLimit()
	{
		$message = new xmlrpcmsg("get_upload_rate");
		$result = $this->client->send($message);
		return round($result->val/1024, 1);
	}
	public function getDownLimit()
	{
		$message = new xmlrpcmsg("get_download_rate");
		$result = $this->client->send($message);
		return round($result->val/1024, 1);
	}
	public function getName($hash)
	{
		return $this->torrents[$hash]['name'];
	}
	public function getDownRate($hash)
	{
		return $this->torrents[$hash]['down_rate'];
	}
	public function getUpRate($hash)
	{
		return $this->torrents[$hash]['up_rate'];
	}
	public function getState($hash)
	{
		return $this->torrents[$hash]['state'];
	}
	public function getConnPeers($hash)
	{
		return $this->torrents[$hash]['peers'];
	}
	public function getConnSeeds($hash)
	{
		return $this->torrents[$hash]['seeds'];
	}
	public function getTotalPeers($hash)
	{
		return $this->torrents[$hash]['peers_scrape'];
	}
	public function getTotalSeeds($hash)
	{
		return $this->torrents[$hash]['seeds_scrape'];
	}
	public function getPercent($hash)
	{
		return $this->torrents[$hash]['percent'];
	}
	public function getRatio($hash)
	{
		return $this->torrents[$hash]['ratio'];
	}
	public function isHashChecking($hash)
	{
		return $this->torrents[$hash]['is_hash_checking'];
	}
	public function getETA($hash)
	{
		return $this->torrents[$hash]['ETA'];
	}
	public function getSize($hash)
	{
		return $this->getCorrectUnits($this->torrents[$hash]['size_in_chunks'] * $this->torrents[$hash]['chunk_size']);
	}
	public function getDone($hash)
	{
		return $this->getCorrectUnits($this->torrents[$hash]['completed_chunks'] * $this->torrents[$hash]['chunk_size']);
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
	private function getPrivate()
	{
		$tt = array();
		$sql = "select hash, user from torrents";
		$result = $this->_db->query($sql);
		$torr = $result->fetchAll();
		foreach($torr as $torrent)
			$tt[$torrent['hash']] = $torrent['user'];
			
		return $tt;
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
    /*private function formatETA($time)
    {
		$time = $time/1000;
		$sec = sprintf("%02d",floor(($time/60 - floor($time/60))*60));
		$min =  sprintf("%02d",floor(($time/3600 - floor($time/3600))*60));
		$hour =  sprintf("%02d",floor(($time/216000 - floor($time/216000))*60));
	
		if($hour > 23)
		{
			$days = floor($hour/24) . "d ";
			$hour = sprintf("%02d",floor(($hour/24 - floor($hour/24))*24));
		} else {
			$days = '';
		}

		return $days . $hour.':'.$min.':'.$sec;
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
    }*/
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