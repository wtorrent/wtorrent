<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty html modifier plugin
 *
 * Type:     modifier<br>
 * Name:     html<br>
 * Purpose:  convert string to html entities
 * @param string
 * @return string
 */
function smarty_modifier_html( $string )
{
	// Inclusion de espacios en cadenas largas
	/*$str = '';
	$i = 0;
	while( $i < strlen( $string ) )
	{
		$f = $i + 30;
		$substr = substr( $string, $i, $f );
		if( ereg( "[^ ]", $substr ) ) $substr .= ' ';
		$i = $f + 1;

		$str .= $substr;
	}
	$string = $str;*/

	// Cadenas vacías
	//if( $string == '' ) return '&nbsp;';

	// Conversion de caracteres HTML y eliminacion del escape de caracteres
	return stripslashes( $string );
    return htmlentities( stripslashes( $string ) );
}
?>