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

Class done by David Marco Martinez
*/

require_once( 'lib/smarty/Smarty.class.php' );
require_once( 'lib/cls/Web.cls.php');
require_once( 'cls/rtorrent.cls.php' );
require_once( 'cls/torrent.cls.php' );
require_once( 'cls/multicall.cls.php' );
//require_once( 'cls/install.cls.php' );
require_once( 'lib/xmlrpc/xmlrpc.inc.php' );
require_once( 'lib/inc/utils.inc.php' );
require_once( 'lib/inc/string.inc.php' );
require_once( 'lib/bdecode/class.bdecode.php' );
require_once( 'lib/bdecode/bencode.php' );
require_once( 'lib/simplepie/simplepie.inc.php' );
require_once( 'lib/json/json.inc.php' );

error_reporting( E_ALL ^ E_NOTICE);

?>