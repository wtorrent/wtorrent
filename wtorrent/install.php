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
require_once("lib/cls/Web.cls.php");
require_once("cls/install.cls.php");
error_reporting(E_ALL);
// Record the start time of execution
$mtime = microtime( true );
// Start php session BEFORE ANY output
session_start();
// Load conf
//require_once( 'conf/user.conf.php' );
install::setDefines();
require_once( 'conf/system.conf.php' );
// Build the base of the app
$web = Web::getClass( 'install' );
// Page specific operations and display
$web->display( 'install/index' );

// Record end time
$ftime = microtime( true );
$total = $ftime -$mtime;
//echo "total: " . $total . 's<br />';
// If end time is shorter than 0.5 segons sleep untill then
if($total < MIN_TIME) {
	usleep(floor((MIN_TIME - $total)*1000000));
	//echo 'lost time: ' . (MIN_TIME - $total) . 's<br />';
}
// REAL end time (Should be about MIN_TIME + 0.1s)
$fftime = microtime( true );
$total = $fftime - $mtime;
?>