<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * Tlex.class.php
	 * - Tlex main core class
	 */

	class Tlex {
		
		static private $_inited = false;

		static public function init() {
			if (self::$_inited) return;
			$_inited = true;


			Tlex_LocaleHandler::init();
			Tlex_CacheHandler::complieExtendedFilter();

			require_once TLEX_BASE_PATH . Tlex_CacheHandler::CACHE_DIR . '/extended.filters.php';
		}
		

		static public function render($tplName, $__context=NULL, $zipBlank=TLEX_ZIP_BLANK) {
			if (strpos($tplName, '.') === false || strrpos($tplName, '.') > 10)
				$tplName .= '.' . TLEX_DEFAULT_TEMPLATE_FILE_EXTENSION;
			

			$__filter = new Tlex_ExtendedFilter();

			Tlex_CacheHandler::init();

			if (Tlex_CacheHandler::isCacheUsable($tplName, false) == false) {
				Tlex_CacheHandler::compileTemplate($tplName, false);
				$zipBlank = false;

			}else if ($zipBlank)
				Tlex_CacheHandler::compileTemplate($tplName, true);
			
			require Tlex_CacheHandler::getCacheFilePath( $tplName, $zipBlank );

		}

	}