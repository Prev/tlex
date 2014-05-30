<?php

	class Tlex_Filter {
		
		public function add($value, $param) {
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

		public function capfirst($value) {
			return ucfirst($value);
		}

		public function center($value, $num) {
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
			while (strpos($value, $del) !== false)
				$value = str_replace($del, '', $value);

			return $value;
		}

	}