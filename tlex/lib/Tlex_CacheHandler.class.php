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

		static public function compileTemplate($tplName, $zipBlank) {
			if (!self::isCacheUsable($tplName, $zipBlank)) {
				//make cache

				$html = file_get_contents($tplName);
				$html = Tlex_TemplateHandler::compileTemplate($html, $tplName, $zipBlank);
				
				$fp = fopen(self::getCacheFilePath($tplName, $zipBlank), 'w');
				fwrite($fp, $html);
				fclose($fp);

				return true;
			}
			return false;
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

		static public function getCacheFilePath($tplName, $zipped=false) {
			$relPath = $_SERVER['SCRIPT_FILENAME'];
			$offset = strrpos($relPath, '/');
			$relPath = substr($relPath, 0, $offset);

			if (strpos($tplName, TLEX_BASE_PATH) === 0)
				$baseTplName = '__tlex__' . basename($tplName);
			else
				$baseTplName = urlencode($tplName);

			return TLEX_BASE_PATH . self::CACHE_DIR . '/' .urlencode(($zipped ? 'zipped_' : '') . $relPath . '/' . $baseTplName);
		}

		static public function isCacheUsable($tplName, $zipBlank=false) {
			$cachePath = self::getCacheFilePath($tplName, $zipBlank);
			return is_file($cachePath) && filemtime($cachePath) > filemtime($tplName);
		}

		static public function deleteCache($tplName) {

			$f1 = self::getCacheFilePath($tplName, false);
			$f2 = self::getCacheFilePath($tplName, true);

			if (is_file($f1)) @unlink($f1);
			if (is_file($f2)) @unlink($f2);
		}

	}