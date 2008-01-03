<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {php5} function plugin
 *
 * Type:     function<br>
 * Name:     php5<br>
 * Purpose:  evaluate a template variable as a php5 code<br>
 * @link http://smarty.php.net/manual/en/language.function.php5.php {eval}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param array
 * @param Smarty
 */
function smarty_function_php5($params, &$smarty)
{
    if (!isset($params['obj'])) {
        $smarty->trigger_error("eval: missing 'obj' parameter");
        return;
    }

    if (!isset($params['str'])) {
        $smarty->trigger_error("eval: missing 'str' parameter");
        return;
    }

    eval( '$rs = $params[\'obj\']->'.$params['str'].';' );
    return $rs;
}

/* vim: set expandtab: */

?>