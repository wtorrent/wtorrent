<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Modifica el numero al formato Sin Decimales y con Separador de Miles
 *
 * Type:     modifier<br>
 * Name:     num<br>
 * Purpose:
 */
function smarty_modifier_num($numero)
{
	return formatNum( $numero );
}

/* vim: set expandtab: */

?>
