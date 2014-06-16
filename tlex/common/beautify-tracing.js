(function () {
	window.addEventListener('load', function() {
		var traces = document.getElementsByClassName("tlex-var-trace");
		for (var i=0; i<traces.length; i++) {
			var str = traces[i].innerHTML;
			str = str.replace(/( *)\[(.*)\]=/g, '$1[<b>$2</b>]=');
			str = str.replace(/( *)(array|object)(.*?){\n/g, '$1<span class="vtrace-keyword">$2</span>$3<b>{</b>\n');
			str = str.replace(/( *)string\(([0-9]*)\)\s"([\S\s]*?)"\n/g, '$1<span class="vtrace-keyword">string</span>($2) <span class="vtrace-string">"$3"</span>\n');
			str = str.replace(/( *)int\(([0-9]*)\)\n/g, '$1<span class="vtrace-keyword">int</span>(<span class="vtrace-light">$2</span>)\n');
			str = str.replace(/( *)bool\((true|false)\)\n/g, '$1<span class="vtrace-keyword">bool</span>(<span class="vtrace-light"><b>$2</b></span>)\n');
			str = str.replace(/( *)NULL\n/g, '$1<span class="vtrace-light"><i>NULL</i></span>\n');
			str = str.replace(/( *)}\n/g, '$1<b>}</b>\n');
			
			traces[i].innerHTML = str;
		}
	});
})();