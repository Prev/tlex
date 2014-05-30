# tlex
the simplest and powerful php template excuter.


# how to use
just run `Tlex::render($templateFileName, $context)`

```php
<?php
  $context = new StdClass();
  $context->foo = 'foooooo';
	$context->fruits = array('apple', 'banana', 'lemon');
	
  Tlex::render('example.thtml', $context);
```


# functions
`{% //php code %}`		--> use php raw code
`{$foo}`				    	--> print variable
`{$foo|trim}`			  	--> print variable with filter(pipe) (like django)
`{count($foo)}`		  	--> excute function and print return value
`{@@@$foo}`			    	--> trace(var_dump) variable


# license
MIT LICENSE
