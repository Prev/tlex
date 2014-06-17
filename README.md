# What is Tlex
Tlex is simple and powerful php template excuter.


## How to use
just excute `Tlex::render($templateFileName, $context)`

```php
<?php
	require 'tlex/tlex.php';
  
	$context = new StdClass();
	$context->foo = 'foooooo';
	$context->fruits = array('apple', 'banana', 'lemon');
	
	Tlex::render('example.thtml', $context);
?>
```


## Template Functions
+ `{% //php code %}`		--> use php raw code
+ `{$foo}`					--> print variable
+ `{$foo|trim}`			  	--> print variable with filter(pipe) (like django)
+ `{count($foo)}`		  	--> excute function and print return value
+ `{@@@$foo}`			    --> trace(var_dump) variable
+ `{[ en=>'english description', ko=>'korean description' ]}`		--> process multi-language
+ `{~'example.css'}`		-> include css (convert to link tag)
+ `{~'example.js'}`			-> include js (convert to link tag)


## Filters ★
You can use all of django built-in filters.

[view django built-in filter](https://docs.djangoproject.com/en/dev/ref/templates/builtins/#built-in-filter-reference)



## Template Example
It's almost like django template

```html
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>tlex example</title>

		{~debugset} <!-- When trace variable (@@@$var), it looks more beautiful -->
		{~'example.css'}
	</head>
	<body>
		<!-- print variable -->
		{$foo}
	
		<!-- excute function -->
		{printLicense()}
		
		<!-- apply filter (like django) -->
		{$foo|capfirst}<br>
		{$foo|center:"15"}<br>
		{$foo|cut:"o"}<br>
		{$people|dictsort:"name"|arraystrfy}<br>
		{'oh!<br>'|escape}<br>
		{$people|first|first}<br>
		{$people|length}<br>
		{"dasd\n\ndas\naa"|linebreaks}
		{'Check out github.com/Prev/tlex'|urlize}<br>
		
		<!-- for loop -->
		<ul>
		{% for ($i=1; $i<=5; $i++) : %}
			<li>{$i}</li>
		{% endfor; %}
		</ul>
	
		<!--  print array by foreach  -->
		<ul>
		{% foreach ($fruits as $key => $item) : %}
			<li>{$item}</li>
			{% endforeach; %}
		</ul>
		
		<!-- trace(var_dump) variable -->
		{@@@$foo}

		<!-- multi-language -->
		{['en'=>'this is english description', 'ko'=>'한국어 설명']}

		<!-- Comment out -->
#		This message wouldn't be shown
#		Haha

	</body>
</html>

```


## Make custom filters, functions
You can add filters and functions to `tlex/user-extensions`

```php
<?php
	//register function as filter (public function)
	@regit
	function customFilter($value) {
	}
	
	//do not register function as filter (private function)
	@hidden
	function notFilter($value) {
	}
?>
```


## License
[MIT LICENSE](https://github.com/Prev/tlex/blob/master/LICENSE)

