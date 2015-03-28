(function () {
	window.addEventListener('load', function() {
		var traces = document.getElementsByClassName("tlex-var-trace");
		for (var i=0; i<traces.length; i++) {
			var str = traces[i].innerHTML;
			
			str = str.replace(/( *)\[(.*)\]=(?:>|&gt;)\n(\s*)/g, function (match, p1, p2, p3) {
				p1 = p1.split("  ").join('<span class="tlex-var-trace-space"><i></i></span>');
				return p1 + '[<span class="tlex-var-trace-keyword">' + p2 + '</span>] <span class="sub">=></span> ';
			});
			
			str = str.replace(/( *)(array|object)(.*?){\n/g, '$1<span class="tlex-var-trace-vartype2">$2</span>$3<span class="tlex-var-trace-vartype2">{</span>\n');
			str = str.replace(/( *)string\(([0-9]*)\)\s"([\S\s]*?)"\n/g, function (match, p1, p2, p3) {
				p3 = p3.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;");

				return p1 + '<span class="tlex-var-trace-vartype">string</span>('+p2+') <b>"</b><span class="tlex-var-trace-string">'+p3+'</span><b>"</b>\n';
			});
			str = str.replace(/( *)int\((\-?[0-9]*)\)\n/g, '$1<span class="tlex-var-trace-vartype3">int</span>(<span class="tlex-var-trace-numric">$2</span>)\n');
			str = str.replace(/( *)bool\((true|false)\)\n/g, '$1<span class="tlex-var-trace-vartype3">bool</span>(<span class="tlex-var-trace-bool">$2</span>)\n');
			str = str.replace(/( *)NULL\n/g, '$1<span class="tlex-var-trace-bool"><i>NULL</i></span>\n');
			
			//str = str.replace(/( *)}\n/g, '$1<span class="tlex-var-trace-vartype2">}</span>\n');
			str = str.replace(/( *)}(\n?)/g, function (match, p1, p2) {
				p1 = p1.split("  ").join('<span class="tlex-var-trace-space"><i></i></span>');
				return p1 + '<span class="tlex-var-trace-vartype2">}</span>' + p2;
			});
			
			traces[i].innerHTML = str;
		}
	});
})();