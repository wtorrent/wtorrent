<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {replace} function plugin
 *
 * Type:     function<br>
 * Name:     replace<br>
 * Purpose:  str_replace<br>
 * @link http://smarty.php.net/manual/en/language.function.replace.php {eval}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param array
 * @param Smarty
 */
function smarty_function_replace( $params, &$smarty )
{
    if( !isset( $params['str'] ) )
    {
        $smarty->trigger_error( 'eval: missing "str" parameter' );
        return;
    }

    if( count( $params ) < 2 )
    {
        $smarty->trigger_error( 'eval: missing parameters' );
        return;
    }

    $str = $params['str'];
    unset( $params['str'] );

    return str_replace( array_keys( $params ), $params, $str );
}

/* vim: set expandtab: */

?>