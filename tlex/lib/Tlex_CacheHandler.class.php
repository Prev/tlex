<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * CacheHandler.class.php
	 * - handling cache
	 */

	class Tlex_CacheHandler {

		const CACHE_DIR = '/cache';

		static public function init() {
			if (!is_writable(TLEX_BASE_PATH . self::CACHE_DIR)) {
				echo '<title>Permission error - tlex</title><center><h2>Permission error</h2>There is no write permission in "/tlex/cache" directory<br>Give write permission to "tlex/cache"</center>';
				exit;
			}

			if (!is_dir(TLEX_BASE_PATH . self::CACHE_DIR))
				mkdir(TLEX_BASE_PATH . self::CACHE_DIR);
			
		}

		static public function compileTemplate($tplName) {
			if (!self::isCacheUsable($tplName)) {
				//make cache

				$html = file_get_contents($tplName);
				$html = Tlex_TemplateHandler::compileTemplate($html, $tplName);
				
				$fp = fopen(self::getCacheFilePath($tplName), 'w');
				fwrite($fp, $html);
				fclose($fp);
			}
		}

		static public function complieExtendedFilter() {
			$cachePath = TLEX_BASE_PATH . self::CACHE_DIR . '/extended.filters.php';
			$originPath = TLEX_BASE_PATH . '/user-extensions/filters.php';

			if (! (is_file($cachePath) && filemtime($cachePath) > filemtime($originPath))  ) {
				$s = file_get_contents(TLEX_BASE_PATH . '/user-extensions/filters.php');
				$s = preg_replace('/@regit\s*\n\s*function/', 'public function', $s);
				$s = preg_replace('/@hidden\s*\n\s*function/', 'private function', $s);
				$s = str_replace('<?php', '', $s);
				$s = '<?php'."\nclass Tlex_ExtendedFilter extends Tlex_Filter{ " . $s . ' }';

				$fp = fopen($cachePath, 'w');
				fwrite($fp, $s);
				fclose($fp);
			}
		}

		static public function getCacheFilePath($tplName) {
			$relPath = $_SERVER['SCRIPT_FILENAME'];
			$offset = strrpos($relPath, '/');
			$relPath = substr($relPath, 0, $offset);

			return TLEX_BASE_PATH . self::CACHE_DIR . '/' .urlencode($relPath . '/' . basename($tplName));
		}

		static private function isCacheUsable($filename) {
			if (TLEX_ALWAYS_MAKE_CACHE) return false;

			$cachePath = self::getCacheFilePath($tplName);
			return is_file($cachePath) && filemtime($cachePath) > filemtime($tplName);
		}

	}