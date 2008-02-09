<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {prueba} function plugin
 *
 * Type:     function<br>
 * Name:     prueba<br>
 * Purpose:  prueba de templates<br>
 *
 * @author
 * @param mixed
 * @param Smarty
 */
function smarty_function_prueba($params, &$smarty)
{

    echo '-->'.get_class( $params['var'] );
}

?>
