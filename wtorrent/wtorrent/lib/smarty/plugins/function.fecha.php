<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty fecha modifier plugin
 *
 * Type:     modifier<br>
 * Name:     fecha<br>
 * Purpose:  convert date
 * @param string
 * @return string
 */
function smarty_modifier_fecha($string)
{
	$f = explode( '-', $string );
	return $f[2].'-'.$f[1].'-'.$f[0];
}

?>

