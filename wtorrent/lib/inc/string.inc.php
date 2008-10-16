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
		return $var;
	}
	return _unescape_internal($var);
}
function html( $str )
{
	$str = str_replace( array( 'Ò', '—' ), array( '&ntilde;', '&Ntilde;' ), $str );
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
	'Ä' => '&#128;',
	'Ç' => '&#130;',
	'å' => '&#140;',
	'é' => '&#142;',
	'ë' => '&#145;',
	'í' => '&#146;',
	'ì' => '&#147;',
	'î' => '&#148;',
	'ï' => '&#149;',
	'ñ' => '&#150;',
	'ó' => '&#151;',
	'ò' => '&#152;',
	'ô' => '&#153;',
	'ö' => '&#154;',
	'õ' => '&#155;',
	'ú' => '&#156;',
	'ü' => '&#159;',
	'°' => '&iexcl;',
	'¢' => '&cent;',
	'£' => '&pound;',
	'§' => '&curren;',
	'•' => '&yen;',
	'¶' => '&brvbar;',
	'ß' => '&sect;',
	'®' => '&uml;',
	'©' => '&copy;',
	'™' => '&ordf;',
	'´' => '&laquo;',
	'¨' => '&not;',
	'≠' => '&shy;',
	'Æ' => '&reg;',
	'Ø' => '&macr;',
	'∞' => '&deg;',
	'±' => '&plusmn;',
	'≤' => '&sup2;',
	'≥' => '&sup3;',
	'¥' => '&acute;',
	'µ' => '&micro;',
	'∂' => '&para;',
	'∑' => '&middot;',
	'∏' => '&cedil;',
	'π' => '&sup1;',
	'∫' => '&ordm;',
	'ª' => '&raquo;',
	'º' => '&frac14;',
	'Ω' => '&frac12;',
	'æ' => '&frac34;',
	'ø' => '&iquest;',
	'¿' => '&Agrave;',
	'¡' => '&Aacute;',
	'¬' => '&Acirc;',
	'√' => '&Atilde;',
	'ƒ' => '&Auml;',
	'≈' => '&Aring;',
	'∆' => '&AElig;',
	'«' => '&Ccedil;',
	'»' => '&Egrave;',
	'…' => '&Eacute;',
	' ' => '&Ecirc;',
	'À' => '&Euml;',
	'Ã' => '&Igrave;',
	'Õ' => '&Iacute;',
	'Œ' => '&Icirc;',
	'œ' => '&Iuml;',
	'–' => '&ETH;',
	'—' => '&Ntilde;',
	'“' => '&Ograve;',
	'”' => '&Oacute;',
	'‘' => '&Ocirc;',
	'’' => '&Otilde;',
	'÷' => '&Ouml;',
	'◊' => '&times;',
	'ÿ' => '&Oslash;',
	'Ÿ' => '&Ugrave;',
	'⁄' => '&Uacute;',
	'€' => '&Ucirc;',
	'‹' => '&Uuml;',
	'›' => '&Yacute;',
	'ﬁ' => '&THORN;',
	'ﬂ' => '&szlig;',
	'‡' => '&agrave;',
	'·' => '&aacute;',
	'‚' => '&acirc;',
	'„' => '&atilde;',
	'‰' => '&auml;',
	'Â' => '&aring;',
	'Ê' => '&aelig;',
	'Á' => '&ccedil;',
	'Ë' => '&egrave;',
	'È' => '&eacute;',
	'Í' => '&ecirc;',
	'Î' => '&euml;',
	'Ï' => '&igrave;',
	'Ì' => '&iacute;',
	'Ó' => '&icirc;',
	'Ô' => '&iuml;',
	'' => '&eth;',
	'Ò' => '&ntilde;',
	'Ú' => '&ograve;',
	'Û' => '&oacute;',
	'Ù' => '&ocirc;',
	'ı' => '&otilde;',
	'ˆ' => '&ouml;',
	'˜' => '&divide;',
	'¯' => '&oslash;',
	'˘' => '&ugrave;',
	'˙' => '&uacute;',
	'˚' => '&ucirc;',
	'¸' => '&uuml;',
	'˝' => '&yacute;',
	'˛' => '&thorn;',
	'ˇ' => '&yuml;',
	'å' => '&OElig;',
	'ú' => '&oelig;',
	'ä' => '&Scaron;',
	'ö' => '&scaron;',
	'ü' => '&Yuml;',
	'à' => '&circ;',
	'ò' => '&tilde;',
	'ñ' => '&ndash;',
	'ó' => '&mdash;',
	'ë' => '&lsquo;',
	'í' => '&rsquo;',
	'Ç' => '&sbquo;',
	'ì' => '&ldquo;',
	'î' => '&rdquo;',
	'Ñ' => '&bdquo;',
	'Ü' => '&dagger;',
	'á' => '&Dagger;',
	'â' => '&permil;',
	'ã' => '&lsaquo;',
	'õ' => '&rsaquo;' );
}
?>
