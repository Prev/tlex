<?php

	require 'tlex/tlex.php';

	$obj = new StdClass();
	$obj->foo = 'foooooo';
	$obj->fruits = array('apple', 'banana', 'lemon');

	function printLicense($detail=false) {
		if ($detail)
			return 'Copyright (c) 2014 prevdev@gmail.com
				Permission is hereby granted, free of charge, to any person
				obtaining a copy of this software and associated documentation
				files (the "Software"), to deal in the Software without
				restriction, including without limitation the rights to use,
				copy, modify, merge, publish, distribute, sublicense, and/or sell
				copies of the Software, and to permit persons to whom the
				Software is furnished to do so, subject to the following
				conditions:.....';
		else
			return 'MIT';
	}

	Tlex::render('example.thtml', $obj);