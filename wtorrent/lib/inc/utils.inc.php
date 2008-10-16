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

// Funcion de autodeclaracion de clases. Atributos indeterminados
function stringForJavascript($in_string) {
   $str = ereg_replace("[\r\n]", " \\n\\\n", $in_string);
   $str = ereg_replace('"', '\\"', $str);
   Return $str;
}
function autoload( )
{
	foreach( func_get_args( ) as $dir )
	{
		$str .= 'if(file_exists(\'' . $dir . '/\' . $file)) require_once(\'' . $dir . '/\' . $file);';
	}
	$cmd =
	'function __autoload( $cls )
	{
		$file = ucfirst( $cls ).\'.cls.php\';
		'.$str.'
	}';

	eval( $cmd );
}
// Control de HTTP_SERVER_VARS
if( !function_exists( 'updateMagicQuotes' ) )
{
	function updateMagicQuotes( $HTTP_VARS )
	{
		if( is_array( $HTTP_VARS ) )
		{
			foreach( $HTTP_VARS as $name => $value )
			{
				if( !is_array( $value ) ) $HTTP_VARS[$name] = addslashes( $value );
				else
				{
					foreach( $value as $name1 => $value1 )
					{
						if( !is_array( $value1 ) )
							$HTTP_VARS[$name1][$value1] = addslashes( $value1 );
					}
				}
				global $$name;
				$$name = &$HTTP_VARS[$name];
			}
		}
		return $HTTP_VARS;
	}

	if( !get_magic_quotes_gpc( ) )
	{
		/*$HTTP_GET_VARS = updateMagicQuotes( $HTTP_GET_VARS );
		$HTTP_POST_VARS = updateMagicQuotes( $HTTP_POST_VARS );
		$HTTP_COOKIE_VARS = updateMagicQuotes( $HTTP_COOKIE_VARS );*/
		$HTTP_GET_VARS = updateMagicQuotes( $_GET );
		$HTTP_POST_VARS = updateMagicQuotes( $_POST );
		$HTTP_COOKIE_VARS = updateMagicQuotes( $_COOKIE );
	}
}

if( !isset($HTTP_SERVER_VARS['REQUEST_URI'] ) )
{
	$HTTP_SERVER_VARS['REQUEST_URI'] = $_SERVER['PHP_SELF'];
}
?>