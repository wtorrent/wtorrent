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

Class made by David Marco Martinez
*/
abstract class Web
{
	// Clase que engloba TODAS las clases (las que implementan formularios)
    // Declaramos todos los atributos comunes con el prefijo _
    private $_lang			= LANGUAGE ;
    private $_smarty		= null;
    private $_caching		= false;
	private $message		= array( );
    
    protected $_str			= array( );
    protected $_sesion		= null;
    protected $_request		= null;
    protected $_post		= null;
    protected $_get			= null;
    protected $_cookie		= null;
    protected $_globals		= null;
    protected $_server		= null;
    protected $_files		= null;
    protected $_env			= null;
    protected $_db			= null;
    protected $_ajax		= false;
    
    /////////////////////////////////// C O N S T R U C T O R A S  Y  D E S T R U C T O R A ///////////////////////////////////

    public function __construct( )
    {
		// Connect to DB
		$this->_db = new SQLiteDatabase(DB_FILE);
    	
    	// Instancia de plantilla Smarty
        $this->_smarty					= new Smarty( );
		$this->_smarty->template_dir	= DIR_TPL;
        $this->_smarty->compile_dir		= DIR_TPL_COMPILE;
        $this->_smarty->debugging		= false;
		//$this->_smarty->caching     	= true;
		// Forcem la compilacio per cada peticio nova
		$this->_smarty->force_compile = false;
		$this->_smarty->register_modifier('decode', array(&$this, 'decode'));
		$this->_smarty->register_modifier('jsOutput', 'stringForJavascript');

        // Asignacion de constantes a smarty
        foreach( $this->_getConstants( ) as $k => $v ) $this->smartyAssign( $k, $v );
		
        // print_r($_SESSION[APP]);
        // Manejo de sesiones y datos recibidos
		/*if( !isset( $_SESSION[APP] ) ) 
			$_SESSION[APP] = new Sesion;*/
		
		$this->_sesion		= &$_SESSION[APP];
		$this->_request		= escape( $_REQUEST );
		$this->_post		= escape( $_POST );
		$this->_get			= escape( $_GET );
		$this->_cookie		= escape( $_COOKIE );
		$this->_globals		= escape( $_GLOBALS );
		$this->_server		= escape( $_SERVER );
		$this->_files		= escape( $_FILES );
		$this->_env			= escape( $_ENV );
		
		 $this->_loadTexts( $this->getLang( ) );
		 if($this->_request['tpl'] == 'ajax') $this->_ajax = true;
    }

    /**
     * Factoria que instancia una subclase
     *
     * @param string $cls_default
     * @return Web
     */
    final public static function getClass( $cls_default )
    {
    	$cls = $_REQUEST['cls'];

        // Nueva Clase derivada de Web
        if( class_exists( $cls ) ) return new $cls( );

        // Nueva Clase derivada de Web (por defecto)
        if( class_exists( $cls_default ) ) return new $cls_default( );

        // Error
        exit( );
    }

	////////////////////////////////////////////////// C O N S U L T O R A S //////////////////////////////////////////////////

    /**
     * Procesa y muestra una subpagina Web
     * La pagina es mostrada en la plantilla $tpl
     *
     * @param string $tpl
     */
    final public function display( $tpl )
    {
        // Metodo virtual. cada p�gina verifica si se tiene permiso
        // o si la pagina tiene tablas de usuarios/grupos/permisos, se puede implementar de forma com�n
        

        // Constructora de la subclase
        if($this->registrado())
        {
        	$this->setPerm();
        	if($this->_sesion->admin && $this->admin) // Avoid execution of orders by unregistered users
        		$this->construct( );
        	elseif($this->admin !== true)
        		$this->construct( );
        }
        
		$this->smartyAssign( 'web', $this );

        // Mostrar la pagina HTML (sub-pagina con AJAX / pagina principal sin AJAX)
        if( isset( $this->_request['tpl'] ) ) $tpl = $this->_request['tpl'];

        // Compresion de codigo html
		//ob_start( );
		$this->_smarty->display( $tpl.'.tpl.php' );
		/*$html = ob_get_contents( );
		ob_end_clean( );
		$html = str_replace( "\n", '', $html ); // Saltos de linea
		$html = str_replace( "\t", '', $html ); // Tabulaciones
		$html = str_replace( "\r", '', $html ); // Retornos de carro
		$html = ereg_replace( '[[:space:]]+', ' ', $html );
		ob_start( 'ob_gzhandler' );
		echo $html;
		ob_end_flush( );*/
    }

    /**
     * Retorna el nombre de la plantilla a mostrar
     *
     * @return string
     */
    final public function getTpl( )
    {
    	$cls = get_class( $this );
    	$cls[0] = strtolower( $cls[0] );
    	return $cls.'.tpl.php';
    }
	final public function getTplName( )
    {
    	$cls = get_class( $this );
    	$cls[0] = strtolower( $cls[0] );
    	return $cls;
    }
    final public function getCls( )
    {
    	return get_class( $this );
    }
	public function getURL()
	{
		return $_SERVER['REQUEST_URI'];
	}
	function scramble($src)
	{
   		$dst = preg_split('//', $src, -1, PREG_SPLIT_NO_EMPTY);
   		shuffle($dst);
   		return implode('',$dst);
	}
	final public function isAjax()
	{
		return $this->_ajax;
	}
    /**
     * Muestra la plantilla en cache (la crea si no si existe).
     * Retorna cierto si envia datos al buffer de salida. Falso en caso contrario.
     * Utiliza parametros indeterminados para generar el identificador de la pagina
     *
     * @param string $tpl
     * @param string [param1 [, param2 ...]]
     * @return bool
     */
    final public function getCache( $tpl )
    {
    	// Pagina no cacheada. Debe compilarse la plantilla
    	if( !$this->_caching ) return false;

    	// Generacion del identificador de pagina mediante el nombre de la plantilla y parametros indefinidos
    	$str = '';
    	foreach( func_get_args( ) as $arg ) $str .= print_r( $arg, true );
	    $file = DIR_TPL_HTML.$tpl.md5( $str );

	    // Retorna la pagina cacheada. Fin de la plantilla
	    if( file_exists( $file ) )
	    {
	        // Retorna la pagina cacheada. Fin del proceso
			echo @file_get_contents( $file );echo 'CACHING';//finCrono( );
	        return true;
	    }

	    // No existe la pagina cacheada. Se genera y se muestra el cache. Fin de la plantilla
	    $this->_caching = false;
	    echo $html = $this->_smarty->fetch( $tpl.'.tpl.php' );
	    @file_put_contents( $file, $html );
	    $this->_caching = true;
	    return true;
    }
	
    
    /**
     * Elimina una determinada pagina de la cache
     *
     * @return bool
     */
    protected function deleteCache( )
    {
    	// Generacion del identificador de pagina mediante parametros indefinidos
    	$str = '';
    	foreach( func_get_args( ) as $arg ) $str .= print_r( $arg, true );
    	$this->_html_file = md5( $str );

    	// Elimina la pagina cacheada
    	return @unlink( DIR_TPL_HTML.$this->_html_file );
    }

    private function _loadTexts( $lang )
    {
		if( !$fd = @fopen( DIR_LANG.$lang.'.txt', 'r' ) ) return false;

		while( !feof( $fd ) )
		{
			$parts = explode( '=', fgets( $fd, 4096 ), 2 );
			$key = trim( $parts[0] );
			if( !empty( $key ) ) $this->_str[$key] = trim( $parts[1] );
		}
		fclose( $fd );

		$this->smartyAssign( 'str', $this->_str );
		return true;
    }

    private function _getConstants( )
    {
    	$constants = get_defined_constants( true );
    	return $constants['user'];
    }
    final public function getMessages()
    {
    	return implode('<br />', $this->message);
    }
    final public function messagesSet()
    {
    	return !empty($this->message);
    }

    /**
     * Retorna el idioma de la web
     *
     * @return string
     */
    final public function getLang( )
    {
    	return $this->_lang;
    }
	public function decode($str)
	{
		if(is_UTF8($str) != 0)
			$return = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $str);
		else
			$return = $str;
	
		return $return;
	}
    /**
     * Ejecuta una URL interna
     *
     * @param string $url
     */
    protected function curl( $link )
    {
    	$fd = @fopen( URL.$link, 'r' );
		@fclose( $fd );
    }

	

    /**
     * Establece el valor del lenguaje
     *
     * @param string $valor
     */
    final public function setLang( $valor )
    {
    	$this->_lang = $valor;
    }

    /**
     * Asigna variables a plantillas Smarty
     *
     * @param string $nombre
     * @param mixed $valor
     */
    final protected function smartyAssign( $nombre, $valor )
    {
    	$this->_smarty->assign_by_ref( $nombre, $valor );
    }

    


    public function getTitle( )
    {
    	return TITLE;
    }
    public function getMetaTitle( )
    {
    	return META_TITLE;
    }
    public function getMetaKeywords( )
    {
    	return META_KEYWORDS;
    }
    public function getMetaDescription( )
    {
    	return META_DESCRIPTION;
    }
    protected function addMessage($message)
    {
    	$this->message[] = $message; 
    }

	
    abstract protected function construct( );
}
?>
