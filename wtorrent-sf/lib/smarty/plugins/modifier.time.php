<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty time modifier plugin
 *
 * Type:     modifier<br>
 * Name:     time<br>
 * Purpose:  convert date to spanish format
 * @param string
 * @return string
 */
function smarty_modifier_time( $time )
{
	return formatTime( $time );
}
?>