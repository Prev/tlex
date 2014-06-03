<?php
	
	/**
	 * @proj Tlex
	 *		- php template excuter
	 *
	 * @package Tlex
	 * @author prevdev@gmail.com
	 * @copyright Copyright (c) 2014 prev.kr
	 * @license MIT LICENSE
	 *
	 */

	/*
	 * ------------------------------------------
	 *  TEMPLATE TAGS
	 * ------------------------------------------
	 *
	 * {% //php code %}			--> use php raw code
	 * {$foo}					--> print variable
	 * {$foo|trim}				--> print variable with filter(pipe) (like django)
	 * {count($foo)}			--> excute function and print return value
	 * {@@@$foo}				--> trace(var_dump) variable
	 * {[ en=>'english description', ko=>'korean description' ]}	--> multi language process
	 *
	 * ------------------------------------------
	 * UPDATING SOON
	 * ------------------------------------------
	 *
	 * {#css} or {#stylesheets}		-> print imported stylesheets
	 * {#js} or {#javascript}		-> print imported javascripts
	 * {#meta}						-> print imported metatags
	 *
	 */


	error_reporting(E_ALL & ~E_NOTICE);


	define('TLEX', true);

	define('TLEX_BASE_PATH', dirname(__FILE__));

	define('TLEX_DEFAULT_TEMPLATE_FILE_EXTENSION', 'thtml');

	define('TLEX_ALWAYS_MAKE_CACHE', true);

	define('TLEX_DEFAULT_LOCALE', 'en');



	require TLEX_BASE_PATH . '/lib/Tlex.class.php';
	require TLEX_BASE_PATH . '/lib/Tlex_CacheHandler.class.php';
	require TLEX_BASE_PATH . '/lib/Tlex_TemplateHandler.class.php';
	require TLEX_BASE_PATH . '/lib/Tlex_Filter.class.php';
	require TLEX_BASE_PATH . '/lib/Tlex_ErrorHandler.class.php';
	require TLEX_BASE_PATH . '/lib/Tlex_LocaleHandler.class.php';

	require TLEX_BASE_PATH . '/user-extensions/functions.php';


	register_shutdown_function(array('Tlex_ErrorHandler', 'parseShutdownHandler'));
	
	Tlex::init();