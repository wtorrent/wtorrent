<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {display} plugin
 *
 * Type:     function<br>
 * Name:     display<br>
 * Purpose:  display template
 * @author David Marco
 * @param array
 * @param Smarty
 * @return string|null if the assign parameter is passed, Smarty assigns the
 *                     result to a template variable
 */
function smarty_function_disp($params, &$smarty)
{
	foreach( $params as $k => $v ) $smarty->assign_by_ref( $k, $v );
	$smarty->display( $params['tpl'] );
}

/* vim: set expandtab: */

?>
