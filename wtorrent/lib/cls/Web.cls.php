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

Original class done by David Marco Martinez
Modified by Roger Pau Monné
*/
abstract class Web
{
	const MASTER_LANGUAGE	= 'en';

	// Master class
	private $_lang				= LANGUAGE ;
	private $_smarty			= null;
	private $_caching			= false;
	private $message			= array( );

	protected $_str				= array( );
	protected $_session		= null;
	protected $_request		= null;
	protected $_post			= null;
	protected $_get				= null;
	protected $_cookie		= null;
	protected $_globals		= null;
	protected $_server		= null;
	protected $_files			= null;
	protected $_env				= null;
	protected $_db				= null;
	protected $_json			= null;
	protected $_tpl				= null;
	protected $_ajax			= false;

	public function __construct($install = false)
	{
		// Connect to DB (mysqli)

		// Compat: if DB_FILE instead of the DSN is defined then assume SQLITE and make a DSN out of it
		if(!$install)
		{
			if (!defined('DB_DSN') && defined('DB_FILE'))
			{
				define('DB_DSN', 'sqlite:' . DIR_EXEC . DB_FILE);
			}
			$this->_db = new PDOe(DB_DSN, null, null, array(PDO::ERRMODE_EXCEPTION));
		}
		// Instance of Smarty templates system
		$this->_smarty								= new Smarty( );
		$this->_smarty->template_dir	= DIR_TPL;
		$this->_smarty->compile_dir		= DIR_TPL_COMPILE;
		$this->_smarty->debugging			= false;
		//$this->_smarty->caching     	= true;
		// Force a new compile for every request (only for dev)
		$this->_smarty->force_compile = false;
		$this->_smarty->register_modifier('decode', array(&$this, 'decode'));

		// Assign constants with Smarty
		foreach( $this->_getConstants( ) as $k => $v ) $this->smartyAssign( $k, $v );

		$this->_session		= &$_SESSION[APP];
		$this->_request		= unescape($_REQUEST);
		$this->_post		= unescape($_POST);
		$this->_get			= unescape($_GET);
		$this->_cookie		= unescape($_COOKIE);
		$this->_files		= unescape($_FILES);
		$this->_server		= &$_SERVER;
		$this->_env			= &$_ENV;
		
		// Create json class
		$this->_json			= new Services_JSON();
		if($install)
		{
			$this->_lang = 'en';
		}
		$this->loadLanguage( $this->getLang( ) );
		if(isset($this->_request['ajax']))	$this->_ajax = true;
		if(isset($this->_request['tpl']))		$this->_tpl = $this->_request['tpl'];
	}

	/**
		* Get instance of a subclass
		*
		* @param string $cls_default
		* @return Web
		*/
	final public static function getClass( $cls_default )
	{
		$cls = $_REQUEST['cls'];

		// Child class from web
		if( class_exists( $cls ) ) return new $cls( );

		// Child class from web (default)
		if( class_exists( $cls_default ) ) return new $cls_default( );

		// Error
		exit( );
	}

	/**
		* Prcesses and displays a new web page
		* Page displayed using the template $tpl
		*
		* @param string $tpl
		*/
	final public function display( $tpl )
	{   

		$this->setPerm();
		// Subclass constructor
		if($this->isRegistered())
		{
			$this->construct();
		}

		$this->smartyAssign( 'web', $this );

		// Display HTML Page
		if( isset( $this->_request['tpl'] ) ) $tpl = $this->_request['tpl'];
		$this->_smarty->display( $tpl.'.tpl.php' );
	}

	/**
		* Return the name of the template to be used (with or without .tpl.php)
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
	/**
		* Return the name of the current cls
		*
		* @return string
		*/
	final public function getCls( )
	{
		return get_class( $this );
	}
	/**
		* Return the URL of the request (ourselfs)
		*
		* @return string
		*/
	public function getURL()
	{
		return $this->_server['REQUEST_URI'];
	}
	/**
		* Is this a AJAX call?
		*
		* @return bool
		*/
	final public function isAjax()
	{
		return $this->_ajax;
	}

	/**
		* Load language texts
		*
		* @return true
		*/
	private function loadLanguage($lang)
	{
		$master = DIR_LANG . self::MASTER_LANGUAGE . '.txt';
		$user = DIR_LANG . $lang . '.txt';


		if (!is_readable($master))
	       	{
			die("Cannot open master language file! ($master)");
		}

		$this->_str = $this->loadLanguageFile($master);

		if ($master !== $user)
	       	{
			if (!is_readable($user))
			{
				die("Cannot open user language file! ($user)");
			}
			$user = $this->loadLanguageFile($user);
			$this->_str = array_merge($user, array_diff_key($this->_str, $user));
		}

		if (sizeof($this->_str) === 0) {
			die("Failed to load language (no strings found)");
		}
		$this->smartyAssign( 'str', $this->_str );
	}

	private function loadLanguageFile($lang)
	{
		$rv = array();

		$lines = @file($lang);
		// no foreach, as this would mean array copy
		for ($i = 0, $e = sizeof($lines); $i < $e; ++$i)
		{
			$pair = explode('=', $lines[$i], 2);
			if (sizeof($pair) != 2)
			{
				continue;
			}
			$key = trim($pair[0]);
			$val = trim($pair[1]);
			if (empty($key) || empty($val))
		       	{
				continue;
			}
			$rv[$key] = preg_replace(array('/\\\\n/', '/\\\\t/m'), array("\n", "\t"), $val);
		}
		return $rv;
	}

	/**
		* Returns defined constants
		*
		* @return array
		*/
	private function _getConstants( )
	{
		$constants = get_defined_constants( true );
		return $constants['user'];
	}
	/**
		* Returns the language of the webpage
		*
		* @return string
		*/
	public function getLang( )
	{
		return $this->_lang;
	}
	/**
		* Set the language value
		*
		* @param string $valor
		*/
	private function setLang( $valor )
	{
		$this->_lang = $valor;
	}
	/**
		* Assign variables to Smarty templates
		*
		* @param string $nombre
		* @param mixed $valor
		*/
	final protected function smartyAssign( $nombre, $valor )
	{
		$this->_smarty->assign_by_ref( $nombre, $valor );
	}
	/**
		* Return the value of some defined constants used in templates
		*
		* @return string
		*/
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
	/**
		* Return error messages in html format
		*
		* @return string
		*/
	final public function getMessages()
	{
		return implode('<br />', $this->message);
	}
	/**
		* Return true if there are messages in the buffer, otherwise returns false
		*
		* @return bool
		*/
	final public function messagesSet()
	{
		return !empty($this->message);
	}
	/**
		* Add an error/warning to the queue
		*
		* @return none
		*/
	protected function addMessage($message)
	{
		$this->message[] = $message; 
	}
	abstract protected function construct( );
}
?>
