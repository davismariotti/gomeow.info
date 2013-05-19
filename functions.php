<?php

function headIncludes() {
	?>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css" rel="stylesheet">
		<link href="css/main.css" rel="stylesheet">
		<script type = "text/javascript" src = "http://code.jquery.com/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
	<?php
}

function navBar() {
	$parent = basename($_SERVER['PHP_SELF']);
	?>
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="index.php">Gomeow</a>
			<ul class="nav">
				<li <?php if($parent == "index.php") { ?>class="active" <?php } ?>><a href="index.php">Home</a></li>
				<li class="divider-vertical"></li>
				<li><a href="http://dev.bukkit.org/profiles/gomeow/">My Plugins</a></li>
				<li class="divider-vertical"></li>
				<li <?php if($parent == "logs.php") { ?>class="active" <?php } ?>><a href="logs.php">IRC Logs</a></li>
			</ul>
		</div>
	</div>
	<?php
}


?>
