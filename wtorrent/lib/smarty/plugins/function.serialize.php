<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {serialize} function plugin
 *
 * Type:     function<br>
 * Name:     serialize<br>
 * Purpose:  serialize a template variable<br>
 * @link http://smarty.php.net/manual/en/language.function.serialize.php {eval}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param array
 * @param Smarty
 */
function smarty_function_serialize($params, &$smarty)
{
    if (!isset($params['var'])) {
        $smarty->trigger_error("eval: missing 'var' parameter");
        return;
    }

    return htmlentities( ( serialize( $params['var'] ) ) );
}

?>