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
		return $result->errno == 0;
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
		{
			$array_multicall[] = $method['message'];
		}
			
		$responses = $this->client->multicall($array_multicall);

		foreach($this->stack as $key => $method)
		{
			if($this->checkError($responses[$key])) // Check errors
			{
				$this->data[$method['hash']][$method['message']->methodname] = $responses[$key]->val;
			}
			else
		   	{
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
	private function performMulticall($entrypoint, $params, $methods)
	{
		$post = array();
		foreach ($params as $param)
	   	{
			$post[] = new xmlrpcval($param, 'string');
		}
		foreach ($methods as $method)
		{
			$post[] = new xmlrpcval($method . '=', 'string');
		}
		$msg = new xmlrpcmsg($entrypoint, $post);
		$res = $this->client->send($msg);

		return $this->checkError($res) ? $res : false;
	}

	/* rTorrent specific multicall methods */
	public function d_multicall($methods, $view = 'default')
	{
		if (empty($this->data))
	   	{
			return true;
		}

		$result = $this->performMulticall('d.multicall', array($view), $methods);
		if ($result === false)
	   	{
			return false;
		}

		$i = 0;
		foreach ($this->data as &$torrent)
		{
			$num = sizeof($result->val[$i]);
			for ($j = 0; $j < $num; ++$j)
			{
				$torrent[$methods[$j]] = $result->val[$i][$j];
			}
			++$i;
		}
		return true;
		
	}
	private function processTorrentMulticall($hash, $entrypoint, $methods)
	{
		$result = $this->performMulticall($entrypoint, array($hash, '0'), $methods);
		if ($result === false)
	   	{
			return false;
		}

		foreach ($result->val as $val)
		{
			for ($i = 0, $e = sizeof($val); $i < $e; ++$i)
			{
				$this->data[$hash][$methods[$i]][] = $val[$i];
			}
		}
		return true;
	}
	public function t_multicall($hash, $methods)
	{
		return $this->processTorrentMulticall($hash, 't.multicall', $methods);
	}
	public function f_multicall($hash, $methods)
	{
		return $this->processTorrentMulticall($hash, 'f.multicall', $methods);
	}
	public function p_multicall($hash, $methods)
	{
		return $this->processTorrentMulticall($hash, 'p.multicall', $methods);
	}
}
?>
