<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * Tlex.class.php
	 * - Tlex main core class
	 */

	class Tlex {
		
		static public function render($tplName, $__context=NULL) {
			if (strpos($tplName, '.') === false || strrpos($tplName, '.') > 10)
				$tplName .= '.' . TLEX_DEFAULT_TEMPLATE_FILE_EXTENSION;

			$__filter = new Tlex_Filter();
			
			Tlex_CacheHandler::init();
			Tlex_CacheHandler::compileTemplate( $tplName );

			require Tlex_CacheHandler::getCacheFilePath( $tplName );

		}

	}