# What is Tlex
Tlex is simple and powerful php template excuter.


# How to use
just excute `Tlex::render($templateFileName, $context)`

```php
<?php
  require 'tlex/tlex.php';
  
  $context = new StdClass();
  $context->foo = 'foooooo';
  $context->fruits = array('apple', 'banana', 'lemon');
	
  Tlex::render('example.thtml', $context);
```


# Template Functions
+ `{% //php code %}`			--> use php raw code
+ `{$foo}`				--> print variable
+ `{$foo|trim}`			  	--> print variable with filter(pipe) (like django)
+ `{count($foo)}`		  	--> excute function and print return value
+ `{@@@$foo}`			    	--> trace(var_dump) variable


# Make custom filters, functions
You can add filters and functions to `tlex/user-extensions/filters.php`, `tlex/user-extensions/functions.php`

# License
MIT LICENSE
