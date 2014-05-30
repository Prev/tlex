<?php
	
	class Tlex_ErrorHandler {

		public static function parseShutdownHandler() {
			$error = error_get_last();

			if (strpos($error['file'], TLEX_BASE_PATH.DIRECTORY_SEPARATOR.'cache') !== false && $error['type'] == E_PARSE) {
				
				self::renderErrorPageWithCode(
					'Template Parse Error',
					$error['message'],
					$error['line'],
					4,
					self::getOriginTemplateFilePath($error['file']),
					$error['file']
				);
			}
		}


		public static function throwCompileError($tplName, $errorMessage, $line, $range=4) {
			self::renderErrorPageWithCode(
				'Template Compile Error',
				$errorMessage,
				$line,
				$range,
				$tplName,
				NULL
			);
		}


		public static function renderErrorPage($errorName, $errorMessage, $contentMessage) {
			$context = new StdClass();
			$context->errorName = $errorName;
			$context->errorMessage = $message;
			$context->contentMessage = $contentMessage;

			Tlex::render(TLEX_BASE_PATH . '/common/error_page', $context);
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
			Tlex::render(TLEX_BASE_PATH . '/common/error_page_with_code', $context);
		}


		private static function getOriginTemplateFilePath($cacheFilePath) {
			$pos = strrpos($cacheFilePath, DIRECTORY_SEPARATOR);
			return urldecode(substr($cacheFilePath, $pos+1));
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