<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty int modifier plugin
 *
 * Type:     modifier<br>
 * Name:     int<br>
 * Purpose:  convert string to int format
 * @param string
 * @return string
 */
function smarty_modifier_int( $int )
{
	// Retorna un entero separado por puntos
	return number_format( $int, 0, '', '.' );
}
?>