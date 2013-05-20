<?php
	$reverse = true;
	if($_GET['reverse'] == "false") {
		$reverse = false;
	}
?>
<html>
	<body>
		<pre><?php
			$text = file_get_contents("../../home/znc/.znc/users/Billy/moddata/log/#". $_GET["file"] . ".log");
			$text = str_replace("<", "&lt;", $text);
			$text = str_replace(">", "&gt;", $text);
			if($reverse == true) {
				echo $text;
			} else {
				$lines = explode("\n", $text);
				$text = "";
				foreach ($lines as $key) {
					$text = "\n" . $key . $text;
				}
				echo substr($text, 1);
			}
		?></pre>
	</body>
</html>