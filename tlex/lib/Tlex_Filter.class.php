<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * Filter.class.php
	 * - Filter(pipe) like django
	 *
	 *
	 * very smiliar with django
	 *		(https://docs.djangoproject.com/en/dev/ref/templates/builtins/#built-in-filter-reference)
	 */

	class Tlex_Filter {

		protected static function throwArgumentMissingError($needArgumentNum=2) {
			$d = debug_backtrace();
			$d = $d[1];
			Tlex_ErrorHandler::renderErrorPageWithCode(
				'Template filter error',
				'Argument missing in calling filter : ' . $needArgumentNum . ' arguments are needed',
				$d['line'],
				4,
				Tlex_ErrorHandler::getOriginTemplateFilePath( $d['file'] ),
				$d['file']
			);
		}

		public function add($value, $param) {
			if (!isset($param)) return self::throwArgumentMissingError();

			switch (gettype($value)) {
				case 'integer':
				case 'double':
				case 'float':
					return $value + $param;
				
				case 'string':
					return $value . $param;

				case 'array':
					return array_merge($value, $param);

				default :
					return NULL;
			}
			
		}

		public function addslashes($value) {
			return addslashes($value);
		}

		public function boolstringfy($value) {
			if ($value === true)
				return 'True';
			else if ($value === false)
				return 'False';
			else
				return $value;
		}

		public function capfirst($value) {
			return ucfirst($value);
		}

		public function center($value, $num) {
			if (!isset($num)) return self::throwArgumentMissingError();

			$num = intval($num);
			$len = strlen($value);

			$n = $num / $len;
			$p = $num % $len;

			$r = '';

			for ($i=0; $i<$n+$p; $i++)
				$r .= ' ';
			$r .= $value;
			for ($i=0; $i<$n; $i++)
				$r .= ' ';

			return $r;
		}

		public function cut($value, $del) {
			if (!isset($del)) return self::throwArgumentMissingError();

			while (strpos($value, $del) !== false)
				$value = str_replace($del, '', $value);

			return $value;
		}

		public function date($value, $format='N j, Y') {
			return date($format, $value);
		}

		public function default2($value, $data) {
			if (!isset($data)) return self::throwArgumentMissingError();

			if (!isset($value) || empty($value) || $value === false)
				return $data;
			else
				return $value;
		}

		public function dictsort($value, $sortKey) {
			if (!isset($sortKey)) return self::throwArgumentMissingError();

			$this->sortKey = $sortKey;
			usort($value, array($this, '_cmp'));
			return $value;
		}
		private function _cmp($a, $b) {
			return ($a->{$this->sortKey} < $b->{$this->sortKey}) ? -1 : 1;
		}

		public function dictsortreversed($value, $sortKey) {
			if (!isset($sortKey)) return self::throwArgumentMissingError();

			$this->sortKey = $sortKey;
			return usort($value, array($this, '_cmpreversed'));
		}
		private function _cmpreversed($a, $b) { 
			return ($a->{$this->sortKey} < $b->{$this->sortKey}) ? 1 : -1;
		}

		public function divisibleby($value, $num) {
			if (!isset($num)) return self::throwArgumentMissingError();

			return $value % $num == 0;
		}

		public function escape($value) {
			return htmlspecialchars($value);
		}

		public function escapejs($value) {
			$new_str = '';
			$str_len = strlen($value);

			for($i = 0; $i < $str_len; $i++) {
				if (preg_match('/[a-zA-Z0-9]/', $value[$i]))
					$new_str .= $value[$i];
				else
					$new_str .= '\\x' . dechex(ord($value[$i]));
			}
			return $new_str;
		}

		public function filesizeformat($value) {
			if ($value > 1024*1024*1024*1024*1024)
				return floor($value/1024/1024/1024/1024/1024*10) / 10 . 'PB';
			else if ($value > 1024*1024*1024*1024)
				return floor($value/1024/1024/1024/1024*10) / 10 . 'TB';
			else if ($value > 1024*1024*1024)
				return floor($value/1024/1024/1024*10) / 10 . 'GB';
			else if ($value > 1024*1024)
				return floor($value/1024/1024*10) / 10 . 'MB';
			else if ($value > 1024)
				return floor($value/1024*10) / 10 . 'KB';
			else
				return floor($value*10) / 10 . 'bytes';
		}

		public function first($value) {
			switch (gettype($value)) {
				case 'integer':
				case 'double':
				case 'float':
					return substr((string)$value, 0, 1);
				
				case 'string':
				case 'array':
					return $value[0];

				case 'object':
					foreach ($value as $key => $d)
						return $d;

				default :
					return NULL;
			}
		}

		public function floatformat($value, $n=1) {
			if ($n < 0) {
				$p = pow(10, abs($n));
				return round($value * $p) / $p;

			}else if ($n > 0) {
				$p = pow(10, $n);
				$r = round($value * $p) / $p;
				if ($r == (int)$r) {
					$r .= '.';
					for ($i=0; $i<$n; $i++)  
						$r .= '0';
					
				}
				return $r;
			}else
				return $value;
		}

		public function iriencode($value) {
			$o = strpos($value, '?');
			$a = substr($value, 0, $o);
			$b = substr($value, $o+1, strlen($value)-$o);
			$b = str_replace('&', '&amp;', $b);
			return $a . $b;
		}

		public function join($value, $del) {
			if (!isset($del)) return self::throwArgumentMissingError();
			return join($del, $value);
		}

		public function last($value) {
			switch (gettype($value)) {
				case 'integer':
				case 'double':
				case 'float':
					$s = (string)$value;
					return substr($s, strlen($s)-1, 1);
				
				case 'string':
				case 'array':
					return $value[count($value)-1];

				case 'object':
					$o;
					foreach ($value as $key => $d)
						$o = $d;
					return $o;
				default :
					return NULL;
			}
		}

		public function length($value) {
			switch (gettype($value)) {
				case 'integer':
				case 'double':
				case 'float':
					return strlen((string)$value);
				
				case 'string':
					return strlen($value);

				case 'array':
				case 'object':
					return count($value);

				default :
					return NULL;
			}
		}

		public function length_is($value, $compare) {
			if (!isset($del)) return self::throwArgumentMissingError();

			return $this->length($value) == $compare;
		}
	}

	