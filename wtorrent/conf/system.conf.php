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

// Permision to set for uploaded .torrent files (don't touch unless you know)
define( 'PERM_TORRENTS',	0777);

// Don't touch any of the data below unless you know what you are doing
define( 'DIR_LANG',			'wt/lang/' );
define( 'DIR_TPL',			'wt/tpl/' );
define( 'DIR_TPL_COMPILE',	'tpl_c/' );
define( 'DIR_TPL_HTML',		'wt/html/' );
define( 'DIR_BACKUP',		'backup/' );
define( 'DIR_UPLOAD', 		'torrents/');
define( 'DIR_CSS',	'wt/css/' );
define( 'DIR_JS',			'wt/js/' );
define( 'DIR_IMG',			'wt/img/' );
define( 'SRC_INDEX',		'index.php' );

define( 'TITLE',			'wTorrent' );
define( 'META_TITLE',		'rTorrent web interface' );
define( 'META_KEYWORDS',	'rtorrent xmlrpc interface php web html' );
define( 'META_DESCRIPTION',	'rtorrent web inrface using xmlrpc' );

// Minimum execution time (due to scriptaculous effects duration)
define( 'MIN_TIME',			 0.6);

define( 'SCRAMBLE',			false);
define( 'APP',				'wTorrent' );

// General libs
require_once( 'lib/inc/includes.inc.php' );

// Autoloading of classes
autoload( 'lib/cls/', 'cls/', 'wt/cls/' );
// UNIX path definition
ini_set( 'include_path',	DIR_EXEC );
?>
