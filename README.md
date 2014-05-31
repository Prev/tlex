# What is Tlex
Tlex is simple and powerful php template excuter.


# How to use
just excute `Tlex::render($templateFileName, $context)`

```php
<?php
  $context = new StdClass();
  $context->foo = 'foooooo';
  $context->fruits = array('apple', 'banana', 'lemon');
	
  Tlex::render('example.thtml', $context);
```


# Functions
+ `{% //php code %}`		--> use php raw code
+ `{$foo}`				    	--> print variable
+ `{$foo|trim}`			  	--> print variable with filter(pipe) (like django)
+ `{count($foo)}`		  	--> excute function and print return value
+ `{@@@$foo}`			    	--> trace(var_dump) variable


# License
MIT LICENSE
