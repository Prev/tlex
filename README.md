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
```


## Template Functions
+ `{% //php code %}`			--> use php raw code
+ `{$foo}`				--> print variable
+ `{$foo|trim}`			  	--> print variable with filter(pipe) (like django)
+ `{count($foo)}`		  	--> excute function and print return value
+ `{@@@$foo}`			    	--> trace(var_dump) variable
+ `{[ en=>'english description', ko=>'korean description' ]}`


## Make custom filters, functions
You can add filters and functions to `tlex/user-extensions`


## License
MIT LICENSE


## Template Example
It's almost like django template

```html
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>tlex example</title>
	</head>
	<body>
		<p>
			<h4>print variable</h3>
			{$foo}
		</p>
		<p>
			<h4>function</h3>
			{printLicense()}
		</p>
		<p>
			<h4>filter (pipe)</h3>
			{$foo|capfirst}<br>
			{$foo|center:"15"}<br>
			{$foo|cut:"o"}<br>
			{$people|dictsort:"name"|arraystrfy}<br>
			{'oh!<br>'|escape}<br>
			{$people|first|first}<br>
			{$people|length}<br>
			{"dasd\n\ndas\naa"|linebreaks}
			{'Check out github.com/Prev/tlex'|urlize}<br>
		</p>

		<p>
			<h4>for loop</h3>
			<ul>
				{% for ($i=1; $i<=5; $i++) : %}
					<li>{$i}</li>
				{% endfor; %}
			</ul>
		</p>
		<p>
			<h4>print array by foreach</h3>
			<ul>
				{% foreach ($fruits as $key => $item) : %}
					<li>{$item}</li>
				{% endforeach; %}
			</ul>
		</p>

		<p>
			<h4>trace(var_dump) variable</h3>
			{@@@$foo}
		</p>

		<p>
			<h4>multi-language</h3>
			{['en'=>'this is english description', 'ko'=>'한국어 설명']}
		</p>

#		Comment out
#
#		This message wouldn't be shown
#		Haha

	</body>
</html>

```


