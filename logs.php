<?php
	require("functions.php");
	$channels = array();
	function startsWith($haystack, $needle){
		return !strncmp($haystack, $needle, strlen($needle));
	}
	if ($handle = opendir('../../home/znc/.znc/users/Billy/moddata/log')) {
		$x = 0;
		while (false !== ($entry = readdir($handle))) {
			if(in_array(substr($entry, 0, -13), $channels)) {
				continue;
			}
			$channels[$x] = substr($entry, 0, -13);
			$x = $x + 1;
		}
		closedir($handle);
	}
	asort($channels);
?>
<html>
	<head>
		<title>IRC Logs</title>
		<?php headIncludes(); ?>
	</head>
	<body>
		<?php navBar(); ?>
		<div class="container">
			<div class="well mainContent">
			<table class="table table-striped table-hover">
					<caption>Channels</caption>
					<tr style="font-weight: bold;">
        				<th>Name</th>
        				<th>Link</th>
     		 		</tr>
     		 		<?php
						foreach($channels as $value) {
							if(startsWith($value, ".") == false and startsWith($value, "#") == true) {
								?>
								<tr><td><?php echo $value; ?></td>
								<td><a class="btn btn-success" href="channel.php?channel=<?php echo substr($value, 1); ?>#b">Click me</a></td></tr>
								<?php
							}
     		 			}
     		 		?>
				</table>
				<small>Powered by Billy</small>
			</div>
		</div>
	</body>
</html>
