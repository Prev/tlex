<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * Locale.class.php
	 * - handling locale (laguage)
	 */

	class Tlex_LocaleHandler {

		public static $usingLocale;

		public static function init() {
			if (isset($_GET['locale']))
				self::$usingLocale = $_GET['locale'];
			else if (isset($_COOKIE['locale']))
				self::$usingLocale = $_COOKIE['locale'];
			else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
				self::$usingLocale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			else
				self::$usingLocale = DEFAULT_LOCALE;
		}

		
		public static function getLocale($compareLocale=NULL) {
			if ($compareLocale)
				return strtolower(self::$usingLocale) == strtolower($compareLocale);
			else
				return strtolower(self::$usingLocale);
		}


		/**
		 * Parse locale
		 * @param $data : object, array, string(json) or string(raw)
		 * 
		 * object: StdClass ('en' => 'English', 'ko' => '한국어')
		 * array: array('en' => 'English', 'ko' => '한국어')
		 * string(json): {"en":"English", "ko":"한국어"}
		 * string(raw): "English"
		 */
		public static function fetchLocale($data) {
			$locale = self::$usingLocale;
			switch (gettype($data)) {
				case 'object' :
					if (isset($data->{$locale}))
						return $data->{$locale};
					else if (isset($data->{TLEX_DEFAULT_LOCALE}))
						return $data->{TLEX_DEFAULT_LOCALE};
					else {
						foreach ($data as $key => $content)
							return $content;
					}
				break;
				
				case 'array' :
					if (isset($data[$locale]))
						return $data[$locale];
					else if (isset($data[TLEX_DEFAULT_LOCALE]))
						return $data[TLEX_DEFAULT_LOCALE];
					else {
						foreach ($data as $key => $content)
							return $content;
					}
				break;
				
				case 'string' :
					if (json_decode($data) !== NULL)
						return fetchLocale(json_decode($data));
					else
						return $data;
					break;
				
				default :
					return $data;
			}
	}

	}