<?php
	
	/**
	 * Make custom filter and register it.
	 *
	 * use @regit identifier to register filter (make public)
	 * use @hidden identifier to hide filter (make private)
	 */


	
	/*
	@regit
	function customFilter($value) {
		if (gettype($value) != 'string') return '';
		return join("#", explode('|', $value));
	}

	@regit
	function customFilter2($value) {
		if (gettype($value) != 'string') return '';

		$r = '';
		for ($i=0; $i<strlen($value); $i++) {
			$r .= $this->getNextChar($value[$i]);
		}
		return $r;
	}

	@hidden
	function getNextChar($value) {
		return chr(ord($value)+1);
	}
	*/