<html>
	<body>
		<pre><?php
			$text = file_get_contents("../../home/znc/.znc/users/Billy/moddata/log/#". $_GET["file"] . ".log");
			$text = str_replace("<", "&lt;", $text);
			$text = str_replace(">", "&gt;", $text);
			echo $text;
		?></pre>
	</body>
</html>