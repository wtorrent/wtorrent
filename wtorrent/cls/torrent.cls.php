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

Created by Roger Pau Monné
*/
class torrent
{
	private $hash;  // saves the hash of the torrent (needed for init)
	private $client;
	private $multicall;
	private $data;
	
	/* Save the hash, don't call any methods by default */
	public function __construct($hash, &$client, &$multicall, &$data)
	{
		$this->hash = $hash;
		$this->client = &$client;
		$this->multicall = &$multicall;
		$this->data = &$data;
	}
	/**
	 * Check return array for errors
	 **/
	private function checkError($result)
	{
		if($result->errno == '0')
			return true;
		else
			return false;
	}
	/**
	 * General functions to prevent duplicate code
	 */
	/* Command Execution (no return value) */
	private function command($command, $multicall)
	{
		$message = new xmlrpcmsg($command, array(new xmlrpcval($this->hash, 'string')));
		
		if($multicall === true)
		{
			$return = $this->multicall->add($message, $this->hash);
		}
		else
		{
			$result = $this->client->send($message);
			$return = $this->checkError($result);
		}
		return $return;
	}
	/* Info request function */
	private function get_info($method, $multicall, $update)
	{
		if($update || !isset($this->data[$method]))
		{
			$message = new xmlrpcmsg($method, array(new xmlrpcval($this->hash, 'string')));
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
	/* Info request function */
	private function get_info_object($method, $param, $multicall, $update)
	{
		if($update || !isset($this->data[$method][$param]))
		{
			$message = new xmlrpcmsg($method, array(new xmlrpcval($this->hash, 'string'), new xmlrpcval($param, 'int')));
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
					$this->data[$method][$param] = $result->val;
					$return = $result->val;
				}
			}
		} else {
			$return = $this->data[$method][$param];
		}
		return $return;
	}
	/**
	* Commands wrappers 
	*/
	public function start($multicall = false)
	{
		return $this->command('d.start', $multicall);
	}
	public function stop($multicall = false)
	{
		return $this->command('d.stop', $multicall);
	}
	public function close($multicall = false)
	{
		return $this->command('d.close', $multicall);
	}
	public function erase($multicall = false)
	{
		return $this->command('d.erase', $multicall);
	}
	public function check_hash($multicall = false)
	{
		return $this->command('d.check_hash', $multicall);
	}
	public function update_priorities($multicall = false)
	{
		return $this->command('d.update_priorities', $multicall);
	}
	/**
	* Info request wrappers
	*/
	/* Download methods */
	public function get_name($multicall = false, $update = false)
	{
		return $this->get_info('d.get_name', $multicall, $update);
	}
	public function get_down_rate($multicall = false, $update = false)
	{
		return $this->get_info('d.get_down_rate', $multicall, $update);
	}
	public function get_up_rate($multicall = false, $update = false)
	{
		return $this->get_info('d.get_up_rate', $multicall, $update);
	}
	public function get_chunk_size($multicall = false, $update = false)
	{
		return $this->get_info('d.get_chunk_size', $multicall, $update);
	}
	public function get_completed_chunks($multicall = false, $update = false)
	{
		return $this->get_info('d.get_completed_chunks', $multicall, $update);
	}
	public function get_size_chunks($multicall = false, $update = false)
	{
		return $this->get_info('d.get_size_chunks', $multicall, $update);
	}
	public function get_state($multicall = false, $update = false)
	{
		return $this->get_info('d.get_state', $multicall, $update);
	}
	public function get_peers_accounted($multicall = false, $update = false)
	{
		return $this->get_info('d.get_peers_accounted', $multicall, $update);
	}
	public function get_peers_complete($multicall = false, $update = false)
	{
		return $this->get_info('d.get_peers_complete', $multicall, $update);
	}
	public function get_peers_connected($multicall = false, $update = false)
	{
		return $this->get_info('d.get_peers_connected', $multicall, $update);
	}
	public function is_hash_checking($multicall = false, $update = false)
	{
		return $this->get_info('d.is_hash_checking', $multicall, $update);
	}
	public function get_ratio($multicall = false, $update = false)
	{
		return $this->get_info('d.get_ratio', $multicall, $update);
	}
	public function get_tracker_size($multicall = false, $update = false)
	{
		return $this->get_info('d.get_tracker_size', $multicall, $update);
	}
	public function is_active($multicall = false, $update = false)
	{
		return $this->get_info('d.is_active', $multicall, $update);
	}
	public function is_open($multicall = false, $update = false)
	{
		return $this->get_info('d.is_open', $multicall, $update);
	}
	public function get_message($multicall = false, $update = false)
	{
		return $this->get_info('d.get_message', $multicall, $update);
	}
	public function get_creation_date($multicall = false, $update = false)
	{
		return $this->get_info('d.get_creation_date', $multicall, $update);
	}
	public function get_size_files($multicall = false, $update = false)
	{
		return $this->get_info('d.get_size_files', $multicall, $update);
	}
	public function get_tied_to_file($multicall = false, $update = false)
	{
		return $this->get_info('d.get_tied_to_file', $multicall, $update);
	}
	public function get_base_path($multicall = false, $update = false)
	{
		return $this->get_info('d.get_base_path', $multicall, $update);
	}
	public function get_peers_max($multicall = false, $update = false)
	{
		return $this->get_info('d.get_peers_max', $multicall, $update);
	}
	public function get_peers_min($multicall = false, $update = false)
	{
		return $this->get_info('d.get_peers_min', $multicall, $update);
	}
	public function d_get_priority($multicall = false, $update = false)
	{
		return $this->get_info('d.get_priority', $multicall, $update);
	}
	public function d_set_priority($priority, $multicall = false)
	{
		$message = new xmlrpcmsg("d.set_priority", array(new xmlrpcval($this->hash, 'string'), new xmlrpcval($priority, 'int')));
		
		if($multicall === true)
		{
			$return = $this->multicall->add($message, $this->hash);
		}
		else
		{
			$result = $this->client->send($message);
			$return = $this->checkError($result);
		}
		return $return;
	}
	public function get_left_bytes($multicall = false, $update = false) {
		return $this->get_info('d.get_left_bytes', $multicall, $update);
	}
	public function get_size_bytes($multicall = false, $update = false) {
		return $this->get_info('d.get_size_bytes', $multicall, $update);
	}
	/* Tracker methods */
	public function t_set_enabled($tracker, $enabled, $multicall = false)
	{
		$message = new xmlrpcmsg('t.set_enabled', array(new xmlrpcval($this->hash, 'string'), new xmlrpcval($tracker, 'int'), new xmlrpcval($enabled, 'int')));
		
		if($multicall === true)
		{
			$return = $this->multicall->add($message, $this->hash);
		}
		else
		{
			$result = $this->client->send($message);
			$return = $this->checkError($result);
		}
		return $return;
	}
	public function t_get_url($tracker, $multicall = false, $update = false)
	{
		return $this->get_info_object('t.get_url', $tracker, $multicall, $update);
	}
	public function t_get_scrape_complete($tracker, $multicall = false, $update = false)
	{
		return $this->get_info_object('t.get_scrape_complete', $tracker, $multicall, $update);
	}
	public function t_get_scrape_incomplete($tracker, $multicall = false, $update = false)
	{
		return $this->get_info_object('t.get_scrape_incomplete', $tracker, $multicall, $update);
	}
	public function t_is_enabled($tracker, $multicall = false, $update = false)
	{
		return $this->get_info_object('t.is_enabled', $tracker, $multicall, $update);
	}
	/* Files methods */
	public function f_set_priority($file, $priority, $multicall = false)
	{
		$message = new xmlrpcmsg('f.set_priority', array(new xmlrpcval($this->hash, 'string'), new xmlrpcval($file, 'int'), new xmlrpcval($priority, 'int')));
		
		if($multicall === true)
		{
			$return = $this->multicall->add($message, $this->hash);
		}
		else
		{
			$result = $this->client->send($message);
			$return = $this->checkError($result);
		}
		return $return;
	}
	public function f_get_path($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('f.get_path', $param, $multicall, $update);
	}
	public function f_get_completed_chunks($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('f.get_completed_chunks', $param, $multicall, $update);
	}
	public function f_get_size_chunks($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('f.get_size_chunks',$param,  $multicall, $update);
	}
	public function f_get_priority($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('f.get_priority', $param, $multicall, $update);
	}
	/* Peers methods */
	public function p_get_address($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.get_address', $param, $multicall, $update);
	}
	public function p_get_down_rate($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.get_down_rate', $param, $multicall, $update);
	}
	public function p_get_up_rate($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.get_up_rate', $param, $multicall, $update);
	}
	public function p_is_incoming($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.is_incoming', $param, $multicall, $update);
	}
	public function p_get_completed_percent($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.get_completed_percent', $param, $multicall, $update);
	}
	public function p_is_encrypted($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.is_encrypted', $param, $multicall, $update);
	}
	public function p_get_peer_rate($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.get_peer_rate', $param, $multicall, $update);
	}
	public function p_get_client_version($param, $multicall = false, $update = false)
	{
		return $this->get_info_object('p.get_client_version', $param, $multicall, $update);
	}
	/* wTorrent specific atributes */
	public function get_private()
	{
		return $this->data['private'];
	}
	public function get_owner()
	{
		return $this->data['owner'];
	}
}
?>
