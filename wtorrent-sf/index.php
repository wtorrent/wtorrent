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

//error_reporting(E_ALL);
/*$mtime = microtime( );
$mtime = explode( ' ', $mtime );
$mtime = $mtime[1] + $mtime[0];*/
session_start();
require_once( 'conf/home.conf.php' );


$web = Web::getClass( 'ListT' );

/*$ttime = microtime( );
$ttime = explode( ' ', $ttime );
$ttime = $ttime[1] + $ttime[0];*/

$web->display( 'index' );

/*$dtime = microtime( );
$dtime = explode( ' ', $dtime );
$dtime = $dtime[1] + $dtime[0];*/
	
/*$ftime = microtime( );
$ftime = explode( ' ', $ftime );
$ftime = $ftime[1] + $ftime[0];
echo "total: " . ($ftime - $mtime) . '<br />';
echo "load info: " . ($ttime - $mtime) . '<br />';
echo "display: " . ($dtime - $ttime) . '<br />';*/
?>