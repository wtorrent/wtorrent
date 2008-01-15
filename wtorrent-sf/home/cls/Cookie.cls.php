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
class Cookie extends rtorrent
{
  private $info = array();
  public function construct()
  {
    if(isset($this->_request['add_cookie']))
    {
      if ($this->_request['cookie_host'] and $this->_request['cookie_value']) {
        $this->addCookie($this->_request['cookie_host'],$this->_request['cookie_value']);
      }
      else 
      {
        $this->addMessage($this->_str['err_add_cookie']);
      }
    }
    if(isset($this->_request['erase'])) $this->DeleteCookie($this->_request['erase']);
    $this->fetchCookies();
  }           
  public function getCookies() {
    return $this->info;
  }
  public function addCookie($host,$value) {
    $sql = "INSERT into cookie (userid,hostname,value) values (" . $this->getIdUser() .",'${host}','${value}');";
    $this->_db->query($sql);
  }
  public function fetchCookies() {
    $sql = "SELECT id,value, hostname FROM cookie where userid = " . $this->getIdUser() . ";";
    $res = $this->_db->query( $sql );
    $result = $res->fetchAll();
    $i = 0;
    foreach($result as $cookie) {
      $this->info[$i]['value']    = $cookie['value'];
      $this->info[$i]['hostname'] = $cookie['hostname'];
      $this->info[$i]['id']       = $cookie['id'];
      $i++;
    }
  }
  public function DeleteCookie($id) {
    	$sql = "DELETE FROM cookie WHERE userid = '". $this->getIdUser() . "' AND id = '$id';";
    	$this->_db->query($sql);
    	$this->addMessage($this->_str['info_erase_cookie']);
  }
}
?>
