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

// Base URL of application (where index.php is) 
define( 'URL',				'http://canbruixa.homelinux.net/wtorrent/' );
define( 'BASE',				'http://canbruixa.homelinux.net/wtorrent/' );

// SQLite database file (set folder permision 0777 or chmod to httpd server) (wTorrent will create database when running install.php)
define( 'DB_FILE',			'db/database.db');

// Host or IP to connect to rTorrent
define( 'RT_HOST',			'localhost');
define( 'RT_PORT',			80);
define( 'RT_DIR',			'RPC2/');
define( 'RT_AUTH',			true);
define( 'RT_USER',			'my_user');
define( 'RT_PASSWD',		'my_password');
// where to use multicall or not
// if wTorrent makes your rtorrent crash, set this to true
define( 'NO_MULTICALL',		true);

// Directory in which to save uploaded .torrent files (set folder permision 0777 or chmod to httpd server)
define( 'DIR_TORRENTS',		'torrents/');

// Full path to application directory (where index.php is)
define( 'DIR_EXEC',			'/var/www/localhost/htdocs/wtorrent/');

// Permision to set for uploaded .torrent files (don't touch unless you know)
define( 'PERM_TORRENTS',	0777);

// Default location to save downloaded files (can be set for every uploaded .torrent on the ui) 
define( 'DIR_DOWNLOAD',		'/data/');

// Default language
define( 'LANGUAGE',             'en');

// Don't touch any of the data below unless you know what you are doing
define( 'DIR_LANG',			'home/lang/' );
define( 'DIR_TPL',			'home/tpl/' );
define( 'DIR_TPL_COMPILE',	'tpl_c/' );
define( 'DIR_TPL_HTML',		'home/html/' );
define( 'DIR_BACKUP',		'backup/' );
define( 'DIR_UPLOAD', 		'torrents/');

define( 'TITLE',			'wTorrent' );
define( 'META_TITLE',		'rTorrent web interface' );
define( 'META_KEYWORDS',	'rtorrent xmlrpc interface php web html' );
define( 'META_DESCRIPTION',	'rtorrent web inrface using xmlrpc' );

define( 'DIR_CSS_DETALLS',	'home/css/detalls.css' );
define( 'DIR_CSS_ESTIL',	'home/css/estil.css' );
define( 'DIR_JS',			'home/js/javasc.js' );
define( 'DIR_JSHADE',		'home/js/shadedborder.js' );
define( 'DIR_JSPROTO',		'home/js/prototype.js' );
define( 'DIR_SCRIPTACULOUS','home/js/scriptaculous/scriptaculous.js');
define( 'DIR_PROTOTIP',     'home/js/prototip.js');
define( 'DIR_FAVICO',		'favicon.ico' );
//define( 'USER_RTORRENT',	'rtorrent');

define( 'DIR_IMG',			'home/img/' );
define( 'SRC_INDEX',		'index.php' );

define( 'SCRAMBLE',			false);
define( 'APP',				'wTorrent' );

// Librerias generales
require_once( 'lib/inc/includes.inc.php' );

// Autodeclaracion de clases
autoload( 'lib/cls/', 'cls/', 'home/cls/' );
// Definicion de rutas para UNIX
ini_set( 'include_path',	DIR_EXEC );
?>
