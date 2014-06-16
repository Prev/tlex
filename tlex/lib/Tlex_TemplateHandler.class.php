<?php
	
	/**
	 * @package Tlex
	 * 
	 *
	 * TemplateHandler.class.php
	 * - parse template
	 */

	class Tlex_TemplateHandler {

		private static $html;
		private static $tplName;

		static public function compileTemplate($html, $tplName) {
			self::$html = $html;
			self::$tplName = $tplName;

			$className = get_class();

			// process comment out
			$html = preg_replace('`\n#(.*)`', "\n", $html);

			// {[ en=>'english description', ko=>'korean description' ]}
			$html = preg_replace('/{\[([\s\S]+?)\]}/', '{% echo Tlex_LocaleHandler::fetchLocale(array($1)); %}', $html);

			// {% // PHP Code %}
			$html = preg_replace_callback('/{%([\s\S]+?)%}/', array($className, 'parseCode'), $html);

			// {$foo|filter}
			$html = preg_replace_callback('/{\s?([^}|]+)((\|[^}|]+)+)\s?}/', array($className, 'parseFilter'), $html);

			// {$foo}
			$html = preg_replace_callback('/{\s?(\$[^}]+?)\s?}/', array($className, 'parseVar'), $html);
			
			// {func()}
			$html = preg_replace_callback("/{\s?([a-zA-Z0-9_]+)\((.*?)\)\s?}/", array($className, 'parseFunc'), $html);
			
			// {@@@$foo}
			$html = preg_replace_callback('/{\s?@@@(\$[^}]+?)\s?}/', array($className, 'parseVarTrace'), $html);
			

			$html = join('$_SERVER', explode('$__context->_SERVER', $html));
			$html = join('$_COOKIE', explode('$__context->_COOKIE', $html));
			$html = join('$GLOBALS', explode('$__context->GLOBALS', $html));
			$html = join('$_GET', explode('$__context->_GET', $html));
			$html = join('$_POST', explode('$__context->_POST', $html));
			$html = join('$_REQUEST', explode('$__context->_REQUEST', $html));
			$html = join('$_SESSION', explode('$__context->_SESSION', $html));
			
			$html = preg_replace('/([a-zA-Z0-9_])::\$__context->(.*)/', '$1::\$$2', $html);


			return $html;
		}

		static protected function parseVar($matches) {
			if (substr($matches[1], 0, 7) == '$__context') return '{'.$matches[1].'}';

			$varname = $matches[1];
			$varname = preg_replace('/\$([\>a-zA-Z0-9_-]*)/', '\$__context->$1', $varname, -1);

			return '<?php echo ' . $varname . '; ?>';
		}

		static protected function parseFilter($matches) {
			$varname = $matches[1];
			$varname = preg_replace('/\$([\>a-zA-Z0-9_-]*)/', '\$__context->$1', $varname, -1);
			$filters_str = substr($matches[2], 1) . '|';
			$filters = array();
			$pof = NULL;
			
			for ($i=0; $i<strlen($filters_str); $i++) { 
				if ($filters_str[$i] == '|') {
					$cn = array();
					$cn[0] = substr($filters_str, 0, $pof ? $pof : $i);
					$cn[1] = ($pof !== NULL) ? substr($filters_str, $pof+1, $i-$pof-1) : NULL;

					array_push($filters, $cn);
					
					$filters_str = substr($filters_str, $i+1);
					$i = 0;
					$pof = NULL;
				}
				else if ($filters_str[$i] == ':') {
					$pof = $i;
				}
				else if ($filters_str[$i] == '"') {
					$i = strpos($filters_str, '"', $i+1);
				}
			}
			$data = $varname;
			for ($i=0; $i<count($filters); $i++) {
				$f = $filters[$i];
				$data = self::getFilterCode($data, $f[0], $f[1]);
			}
	
			return '<?php echo ' . $data . '; ?>';
		}

		static private function getFilterCode($data, $filter, $params=NULL) {
			$params = preg_replace('/\$([\>a-zA-Z0-9_-]*)/', '\$__context->$1', $params, -1);
			return '$__filter->' .$filter . '(' . $data . ($params ? ', '.$params : '') . ')';
		}


		static protected function parseCode($matches) {
			if (!$matches[1]) return;
			
			$c = $matches[1];
			$c = preg_replace('/([^:>])\$([\>a-zA-Z0-9_-]*)/', '$1\$__context->$2', $c);
			$c = preg_replace('/([^:>])\${([\>a-zA-Z0-9_-]*)}/', '$1\${__context->$2}', $c);

			if (substr($c, 0, 1) != ' ') $c = ' ' . $c;
			if (substr($c, strlen($c)+1, 1) != ' ')	$c .= '';
				
			return '<?php' . $c . '?>';
		}

		static protected function parseFunc($matches) {
			if (!$matches[1]) return;
			
			$function = $matches[1];
			$args = $matches[2];
			$args = preg_replace('/\$([\>a-zA-Z0-9_-]*)/', '\$__context->$1', $args, -1);
			$args = preg_replace('/\${([\>a-zA-Z0-9_-]*)}/', '\${__context->$1}', $args, -1);

			return '<?php $func=' . $function.'('.$args.')' . '; if (isset($func)) echo $func; ?>';
		}


		static protected function parseVarTrace($matches) {
			$varname = $matches[1];
			$varname = preg_replace('/\$([\>a-zA-Z0-9_-]*)/', '\$__context->$1', $varname, -1);

			return '<?php echo \'<x y=""><pre class="tlex-var-trace">\'; var_dump('.$varname.'); echo \'</x></pre>\' ?>';
		}

	}