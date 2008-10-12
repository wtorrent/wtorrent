<?php
/*
This file is part of wTorrent.

wTorrent is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

wTorrent is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Class done by David Marco Martinez
*/
/**
 * Verifica si un string esta codificado en UTF-8
 *
 * @param string $str
 * @return bool
 */
function is_UTF8( $str )
{
	return preg_match( '%(?:
		[\xC2-\xDF][\x80-\xBF]				# non-overlong 2-byte
		|\xE0[\xA0-\xBF][\x80-\xBF]			# excluding overlongs
		|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}	# straight 3-byte
		|\xED[\x80-\x9F][\x80-\xBF]			# excluding surrogates
		|\xF0[\x90-\xBF][\x80-\xBF]{2}		# planes 1-3
		|[\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
		|\xF4[\x80-\x8F][\x80-\xBF]{2}		# plane 16
		)+%xs', $str );
}

function _unescape_internal($var)
{
	switch (gettype($var)) {
	case 'array':
		return array_map('_unescape_internal', $var);
	case 'string':
		return stripslashes($var);
	default:
		return $var;
	}
}
function unescape( $var )
{
	if (!get_magic_quotes_gpc())
	{
		return;
	}
	return _unescape_internal($var);
}
function html( $str )
{
	$str = str_replace( array( 'ñ', 'Ñ' ), array( '&ntilde;', '&Ntilde;' ), $str );
	return preg_replace( '/[^\x00-\x7F]/e', '"&#".ord("$0").";"', $str );

	return str_replace( array_keys( getHtmlChars( ) ), getHtmlChars( ), $str );
}

function getHtmlChars( )
{
	return array(
	'!' => '&#33;',
	'"' => '&quot;',
	'#' => '&#35;',
	'$' => '&#36;',
	'%' => '&#37;',
	'&' => '&amp;',
	'\'' => '&#39;',
	'(' => '&#40;',
	')' => '&#41;',
	'*' => '&#42;',
	'+' => '&#43;',
	',' => '&#44;',
	'-' => '&#45;',
	'.' => '&#46;',
	'/' => '&#47;',
	':' => '&#58;',
	';' => '&#59;',
	'<' => '&lt;',
	'=' => '&#61;',
	'>' => '&gt;',
	'?' => '&#63;',
	'@' => '&#64;',
	'[' => '&#91;',
	'\\' => '&#92;',
	']' => '&#93;',
	'^' => '&#94;',
	'_' => '&#95;',
	'`' => '&#96;',
	'{' => '&#123;',
	'|' => '&#124;',
	'}' => '&#125;',
	'~' => '&#126;',
	'€' => '&#128;',
	'‚' => '&#130;',
	'Œ' => '&#140;',
	'Ž' => '&#142;',
	'‘' => '&#145;',
	'’' => '&#146;',
	'“' => '&#147;',
	'”' => '&#148;',
	'•' => '&#149;',
	'–' => '&#150;',
	'—' => '&#151;',
	'˜' => '&#152;',
	'™' => '&#153;',
	'š' => '&#154;',
	'›' => '&#155;',
	'œ' => '&#156;',
	'Ÿ' => '&#159;',
	'¡' => '&iexcl;',
	'¢' => '&cent;',
	'£' => '&pound;',
	'¤' => '&curren;',
	'¥' => '&yen;',
	'¦' => '&brvbar;',
	'§' => '&sect;',
	'¨' => '&uml;',
	'©' => '&copy;',
	'ª' => '&ordf;',
	'«' => '&laquo;',
	'¬' => '&not;',
	'­' => '&shy;',
	'®' => '&reg;',
	'¯' => '&macr;',
	'°' => '&deg;',
	'±' => '&plusmn;',
	'²' => '&sup2;',
	'³' => '&sup3;',
	'´' => '&acute;',
	'µ' => '&micro;',
	'¶' => '&para;',
	'·' => '&middot;',
	'¸' => '&cedil;',
	'¹' => '&sup1;',
	'º' => '&ordm;',
	'»' => '&raquo;',
	'¼' => '&frac14;',
	'½' => '&frac12;',
	'¾' => '&frac34;',
	'¿' => '&iquest;',
	'À' => '&Agrave;',
	'Á' => '&Aacute;',
	'Â' => '&Acirc;',
	'Ã' => '&Atilde;',
	'Ä' => '&Auml;',
	'Å' => '&Aring;',
	'Æ' => '&AElig;',
	'Ç' => '&Ccedil;',
	'È' => '&Egrave;',
	'É' => '&Eacute;',
	'Ê' => '&Ecirc;',
	'Ë' => '&Euml;',
	'Ì' => '&Igrave;',
	'Í' => '&Iacute;',
	'Î' => '&Icirc;',
	'Ï' => '&Iuml;',
	'Ð' => '&ETH;',
	'Ñ' => '&Ntilde;',
	'Ò' => '&Ograve;',
	'Ó' => '&Oacute;',
	'Ô' => '&Ocirc;',
	'Õ' => '&Otilde;',
	'Ö' => '&Ouml;',
	'×' => '&times;',
	'Ø' => '&Oslash;',
	'Ù' => '&Ugrave;',
	'Ú' => '&Uacute;',
	'Û' => '&Ucirc;',
	'Ü' => '&Uuml;',
	'Ý' => '&Yacute;',
	'Þ' => '&THORN;',
	'ß' => '&szlig;',
	'à' => '&agrave;',
	'á' => '&aacute;',
	'â' => '&acirc;',
	'ã' => '&atilde;',
	'ä' => '&auml;',
	'å' => '&aring;',
	'æ' => '&aelig;',
	'ç' => '&ccedil;',
	'è' => '&egrave;',
	'é' => '&eacute;',
	'ê' => '&ecirc;',
	'ë' => '&euml;',
	'ì' => '&igrave;',
	'í' => '&iacute;',
	'î' => '&icirc;',
	'ï' => '&iuml;',
	'ð' => '&eth;',
	'ñ' => '&ntilde;',
	'ò' => '&ograve;',
	'ó' => '&oacute;',
	'ô' => '&ocirc;',
	'õ' => '&otilde;',
	'ö' => '&ouml;',
	'÷' => '&divide;',
	'ø' => '&oslash;',
	'ù' => '&ugrave;',
	'ú' => '&uacute;',
	'û' => '&ucirc;',
	'ü' => '&uuml;',
	'ý' => '&yacute;',
	'þ' => '&thorn;',
	'ÿ' => '&yuml;',
	'Œ' => '&OElig;',
	'œ' => '&oelig;',
	'Š' => '&Scaron;',
	'š' => '&scaron;',
	'Ÿ' => '&Yuml;',
	'ˆ' => '&circ;',
	'˜' => '&tilde;',
	'–' => '&ndash;',
	'—' => '&mdash;',
	'‘' => '&lsquo;',
	'’' => '&rsquo;',
	'‚' => '&sbquo;',
	'“' => '&ldquo;',
	'”' => '&rdquo;',
	'„' => '&bdquo;',
	'†' => '&dagger;',
	'‡' => '&Dagger;',
	'‰' => '&permil;',
	'‹' => '&lsaquo;',
	'›' => '&rsaquo;' );
}
?>
