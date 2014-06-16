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

		public function arraystrfy($value) {
			if (!is_array($value)) return NULL;

			$j = json_encode($value);
			return 'Array(' . substr($j, 1, strlen($j)-2) . ')';
		}

		public function boolstrfy($value) {
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

		public function len($value) {
			return $this->length($value);
		}

		public function length_is($value, $compare) {
			if (!isset($compare)) return self::throwArgumentMissingError();

			return $this->length($value) == $compare;
		}

		public function linebreaks($value) {
			$value = str_replace("\r\n", "\n", $value);
			$value = str_replace("\n\n", '</p><p>', $value);
			$value = '<p>' . $value . '</p>';
			$value = str_replace("\n", '<br>', $value);
			return $value;
		}

		public function linebreaksbr($value) {
			$value = str_replace("\r\n", "\n", $value);
			$value = str_replace("\n", '<br>', $value);
			return $value;
		}

		public function linenumbers($value) {
			$value = str_replace("\r\n", "\n", $value);
			$result = '';
			$arr = explode("\n", $value);
			for ($i=0; $i<count($arr); $i++) 
				$result .= ($i+1) . '. ' . $arr[$i] . "\n";
			return $result;
		}

		public function ljust($value, $fieldsize) {
			if (!isset($fieldsize)) return self::throwArgumentMissingError();

			$value = (string)$value;
			$n = $fieldsize - strlen($value);
			for ($i=0; $i<$n; $i++)
				$value .= ' ';
			return $value;
		}

		public function lower($value) {
			if (!is_string($value)) return NULL;
			return strtolower($value);
		}

		public function make_list($value) {
			$value = (string)$value;
			$arr = array();
			for ($i=0; $i<strlen($value); $i++)
				array_push($arr, $value);
			return $arr;
			
		}

		public function nbsp($value) {
			return str_replace(' ', '&nbsp;', $value);
		}

		public function pluralize($value, $suffix='s') {
			$default = '';
			if (strpos($suffix, ',') !== false) {
				$tmp = explode(',', $suffix);
				$default = $tmp[0];
				$suffix = $tmp[1];
			}

			return ($value > 1) ? $suffix : $default;
		}

		public function random($value) {
			if (!is_array($value)) return NULL;

			$i = rand(0, count($value)-1);
			return $value[$i];
		}

		public function removetags($value, $tags) {
			if (!isset($tags)) return self::throwArgumentMissingError();

			$tags = explode(' ', $tags);
			for ($i=0; $i<count($tags); $i++) { 
				$tag = $tags[$i];
				$value = preg_replace('/<(?:\s|\/?)*'.$tag.'\s*>/', '', $value);
			}
			return $value;
		}

		public function rjust($value, $fieldsize) {
			if (!isset($fieldsize)) return self::throwArgumentMissingError();

			$value = (string)$value;
			$n = $fieldsize - strlen($value);
			for ($i=0; $i<$n; $i++)
				$value = ' ' . $value;
			return $value;
		}

		public function slice($value, $offset) {
			if (!isset($offset)) return self::throwArgumentMissingError();
			if (!is_array($value)) return NULL;

			array_slice($value, $offset);
			return $value;
		}

		public function slugify($value) {
			$value = strtolower($value);
			$value = preg_replace('/[^a-z ]/', '', $value);
			return str_replace(' ', '-', $value);
		}

		public function striptags($value) {
			return strip_tags($value);
		}

		public function time($value, $format='H:i:s') {
			return date($format, $value);
		}

		public function timesince($value, $since) {
			if (!isset($since)) return self::throwArgumentMissingError();

			if (is_string($since)) $since = strtotime($since);
			if (is_string($value)) $value = strtotime($value);

			$diff = $since - $value;
			if ($diff < 3600)
				return (int)($diff / 60) . ' minutes';
			else if ($diff < 86400)
				return (int)($diff / 3600) . ' hours';
			else
				return (int)($diff / 86400) . ' days';
		}

		public function timeuntil($value, $since) {
			if (!isset($since)) return self::throwArgumentMissingError();

			if (is_string($since)) $since = strtotime($since);
			if (is_string($value)) $value = strtotime($value);

			$diff = $value - $since;
			if ($diff < 3600)
				return (int)($diff / 60) . ' minutes';
			else if ($diff < 86400)
				return (int)($diff / 3600) . ' hours';
			else
				return (int)($diff / 86400) . ' days';
		}

		public function title($value) {
			$arr = explode(' ', $value);
			for ($i=0; $i<count($arr); $i++)
				$arr[$i] = ucfirst( strtolower( $arr[$i] ) );
			return join(' ', $arr);
		}

		public function truncatechars($value, $num) {
			if (!isset($num)) return self::throwArgumentMissingError();
			if (!is_string($value)) return NULL;

			$num = intval($num);

			if (strlen($value) <= $num+3)
				return $value;
			else
				return substr($value, 0, $num-3) . '...';
		}

		public function truncatewords($value, $num) {
			if (!isset($num)) return self::throwArgumentMissingError();
			if (!is_string($value)) return NULL;

			$arr = explode(' ', $value);
			$result = '';
			for ($i=0; $i<$num; $i++)
				$result .= $arr[$i] . ' ';
			$result .= '...';
			return $result;
		}

		public function unordered_list($value) {
			/**
				TODO
			*/
		}

		public function upper($value) {
			if (!is_string($value)) return NULL;
			return strtoupper($value);
		}

		public function urlencode($value) {
			if (!is_string($value)) return NULL;
			return urlencode($value);
		}

		public function urldecode($value) {
			if (!is_string($value)) return NULL;
			return urldecode($value);
		}


		private $_url_target;
		private $_urlize_reg = '/(https?:\/\/(?:[a-zA-Z0-9\/._\%\?\=\&\#\+-])+)|([a-zA-Z0-9\/._-]+\.(?:com|net|org|edu|gov|int|mil|kr|io|me|gl|ly)[a-zA-Z0-9\/._\%\?\=\&\#\+-]*)/';
		private $_urlize_trunknum;

		public function urlize($value, $target=NULL) {
			$this->_url_target = $target;

			return preg_replace_callback($this->_urlize_reg, array($this, 'urlize_callback'), $value);
		}

		private function urlize_callback($matches) {
			$targetTag = ($this->_url_target) ? ' target="'.$this->_url_target.'"' : '';
			if ($matches[1])
				return '<a href="' . $matches[1] . '"'.$targetTag.'>'.$matches[1].'</a>';
			else if ($matches[2])
				return '<a href="http://' . $matches[2] . '"'.$targetTag.'>'.$matches[2].'</a>';
			else
				return $matches[0];
		}

		public function urlizetrunc($value, $trunknum, $target=NULL) {
			if (!isset($trunknum)) return self::throwArgumentMissingError();
			$this->_url_target = $target;
			$this->_urlize_trunknum = $trunknum;

			return preg_replace_callback($this->_urlize_reg, array($this, 'urlizetrunc_callback'), $value);
		}

		private function urlizetrunc_callback($matches) {
			$targetTag = ($this->_url_target) ? ' target="'.$this->_url_target.'"' : '';
			if ($matches[1])
				return '<a href="' . $matches[1] . '"'.$targetTag.'>'.$this->truncatechars($matches[1], $this->_urlize_trunknum).'</a>';
			else if ($matches[2])
				return '<a href="http://' . $matches[2] . '"'.$targetTag.'>'.$this->truncatechars($matches[2], $this->_urlize_trunknum).'</a>';
			else
				return $matches[0];
		}

		public function wordcount($value) {
			$arr = explode(' ', $value);
			return count($value);
		}

		public function wordwrap($value, $num) {
			if (!isset($num)) return self::throwArgumentMissingError();

			$n = 0;
			for ($i=0; $i<strlen($value); $i++) { 
				if ($n == $num) {
					if ($value[$i] == ' ')
						$value = substr($value, 0, $i) . "\n" . substr($value, $i+1);
					else
						$value = substr($value, 0, $i) . "\n" . substr($value, $i);
					$n = 0;
				}
				
				$n++;
			}
			return $value;
		}

		public function yesno($value, $arg=NULL) {
			if ($arg == NULL)
				return $value == true ? 'yes' : 'no';
			else {
				$arg = explode(',', $arg);
				if (!isset($arg[2])) $arg[2] = $arg[1];

				if ($value === true)
					return $arg[0];
				else if ($value === false)
					return $arg[1];
				else if ($value == NULL)
					return $arg[2];
				else
					return '';
			}
		}

	}

	