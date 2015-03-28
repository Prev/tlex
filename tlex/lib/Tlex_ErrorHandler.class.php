<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * ErrorHandler.class.php
	 * - Handle shutdown error (parse error in template)
	 * - throw custom error
	 */

	class Tlex_ErrorHandler {

		public static function parseShutdownHandler() {
			$ds = DIRECTORY_SEPARATOR;
			$error = error_get_last();

			if (strpos($error['file'], TLEX_BASE_PATH.$ds.'cache') !== false) {
				if (strpos($error['message'], 'Call to private method') === 0)
					$errorName = 'Call hidden filter';
				else {
					switch ($error['type']) {
						case E_ERROR:
							$errorName = 'Template Fatal Error';
							break;
						
						case E_WARNING:
							$errorName = 'Template Warning';
							break;

						case E_PARSE:
							$errorName = 'Template Parse Error';
							break;

						default:
							return;
					}
				}
				
				ob_clean();
				
				$originTplPath = self::getOriginTemplateFilePath($error['file']);
				$cacheTplPath = $error['file'];

				self::renderErrorPageWithCode(
					$errorName,
					$error['message'],
					$error['line'],
					4,
					$originTplPath,
					$cacheTplPath
				);

				Tlex_CacheHandler::deleteCache($originTplPath);
			}
		}


		public static function renderErrorPage($errorName, $errorMessage, $contentMessage) {
			$context = new StdClass();
			$context->errorName = $errorName;
			$context->errorMessage = $message;
			$context->contentMessage = $contentMessage;

			Tlex::render(TLEX_BASE_PATH . '/common/error_page.thtml', $context);
		}

		
		public static function renderErrorPageWithCode($errorName, $errorMessage, $line, $range, $originTplName, $cachedTplName) {
			$context = new StdClass();
			$context->errorName = $errorName;
			$context->errorMessage = $errorMessage;
			$context->errorLineNum = $line;

			$context->originTplName = $originTplName;
			$context->errorLines = self::getLinesByRange( $context->originTplName, $line, $range );

			if ($cachedTplName) {
				$context->cachedTplName = $cachedTplName;
				$context->errorLines_cached = self::getLinesByRange( $context->cachedTplName, $line, $range );
			}
			Tlex::render(TLEX_BASE_PATH . '/common/error_page_with_code.thtml', $context);
		}


		public static function getOriginTemplateFilePath($cacheFilePath) {
			$pos = strrpos($cacheFilePath, DIRECTORY_SEPARATOR);
			return urldecode(urldecode(substr($cacheFilePath, $pos+1)));
		}

		private static function getLinesByRange($filePath, $offset, $range) {
			$result = array();
			$fp = fopen($filePath, 'rb');
			$n = 1;

			while ($content = fgets($fp)) {
				if ($n >= $offset - $range && $n <= $offset + $range) {
					$content = substr($content, 0, strlen($content) - 2);

					if ($n != $offset + $range)
						$content .= "\n"; 

					array_push($result, $content);
				}
				$n++;
			}

			if ($n == $offset)
				array_push($result, ' ');

			fclose($fp);
			return $result;
		}
		
	}