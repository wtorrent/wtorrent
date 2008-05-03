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
class multicall
{
	private $stack; // saves the multicalls to be executed later on
	private $client;
	private $data;
	
	/* Save the xmlrpc client, don't call any other methods */
	public function __construct($client, &$data)
	{
		$this->client = &$client;
		$this->data = &$data;
	}
	/* Check for errors in xmlrpc repsonse */
	private function checkError($result)
	{
		if($result->errno == '0')
			return true;
		else
			return false;
	}
	/* Add a method to the multicall stack */
	public function add($message, $hash = null)
	{
		$method = array('message' => $message,'hash' => $hash,'method' => $method);
		$this->stack[] = $method;
	}
	/* Execute the multicall stack */
	public function call()
	{
		$array_multicall = array();
		
		foreach($this->stack as $method)
			$array_multicall[] = $method['message'];
			
		$responses = $this->client->multicall($array_multicall);

		foreach($this->stack as $key => $method)
		{
			if($this->checkError($responses[$key])) // Check errors
			{
				$this->data[$method['hash']][$method['message']->methodname] = $responses[$key]->val;
			} else {
				$return = false;
			}
		}
		
		$this->stack = array(); // Empty Stack
		
		if($return !== false) // Error checking
		{
			$return = true;
		}
		return $return;
	}
	/* rTorrent specific multicall methods */
	public function d_multicall($methods, $view = 'default')
	{
		$array_post[] = new xmlrpcval($view, 'string');

		foreach($methods as $param)
			$array_post[] = new xmlrpcval($param . '=', 'string');

		$message = new xmlrpcmsg("d.multicall", $array_post);
		$result = $this->client->send($message);

		if($this->checkError($result))
		{
			$i = 0;
			foreach($this->data as &$torrent)
			{
				$num = count($result->val[$i]);
				for($j = 0; $j < $num; $j++)
				{
					$torrent[$methods[$j]] = $result->val[$i][$j];
				}
				$i++;
			}
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	public function t_multicall($hash, $methods)
	{
		$array_post[] = new xmlrpcval($hash, 'string');
		$array_post[] = new xmlrpcval('0', 'string'); // Dummy argument

		foreach($methods as $param)
			$array_post[] = new xmlrpcval($param . '=', 'string');

		$message = new xmlrpcmsg("t.multicall", $array_post);
		$result = $this->client->send($message);
		
		if($this->checkError($result))
		{
			foreach($result->val as $val)
			{
				$num = count($val);
				for($j = 0; $j < $num; $j++)
				{
					$this->data[$hash][$methods[$j]][] = $val[$j];
				}
				$return = true;
			}
		} else {
			$return = false;
		}
		return $return;
	}
	public function f_multicall($hash, $methods)
	{
		$array_post[] = new xmlrpcval($hash, 'string');
		$array_post[] = new xmlrpcval('0', 'string'); // Dummy argument

		foreach($methods as $param)
			$array_post[] = new xmlrpcval($param . '=', 'string');

		$message = new xmlrpcmsg("f.multicall", $array_post);
		$result = $this->client->send($message);

		if($this->checkError($result))
		{
			foreach($result->val as $val)
			{
				$num = count($val);
				for($j = 0; $j < $num; $j++)
				{
					$this->data[$hash][$methods[$j]][] = $val[$j];
				}
				$return = true;
			}
		} else {
			$return = false;
		}
		return $return;
	}
	public function p_multicall($hash, $methods)
	{
		$array_post[] = new xmlrpcval($hash, 'string');
		$array_post[] = new xmlrpcval('0', 'string'); // Dummy argument

		foreach($methods as $param)
			$array_post[] = new xmlrpcval($param . '=', 'string');

		$message = new xmlrpcmsg("p.multicall", $array_post);
		$result = $this->client->send($message);
		
		if($this->checkError($result))
		{
			foreach($result->val as $val)
			{
				$num = count($val);
				for($j = 0; $j < $num; $j++)
				{
					$this->data[$hash][$methods[$j]][] = $val[$j];
				}
				$return = true;
			}
		} else {
			$return = false;
		}
		return $return;
	}
}
?>