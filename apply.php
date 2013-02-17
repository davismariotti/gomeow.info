<?php
	require("functions.php");
?>

<html>
	<head>
		<title>Apply for Bukkit Community Server</title>
		<?php headIncludes(); ?>
	</head>
	<body>
		<?php navBar(); ?>
		<div class="container">
			<div class="well mainContent">
				<img src="http://forums.bukkit.org/logo.png"></img>
				<h3>Community Server</h6>
				<h5>Conditions:<h5>
				<h6>Meet one of the following:</h6>
				<ul>
					<li>Have at least 800 posts on the Bukkit Forums.</li>
					<li>Have made at least 2 plugins.</li>
				</ul>
				<h6>Meet all of the following as well:</h6>
				<ul>
					<li>Have a premium Minecraft account.</li>
					<li>Have a valid Bukkit account</li>
				</ul>
				<h6>These will all be verified!</h6>
				<?php 
					if($_GET['e'] == 1) { ?><div class="alert alert-error">You need to fill in your Minecraft Username!</div><?php }
					if($_GET['e'] == 2) { ?><div class="alert alert-error">You need to fill in your Bukkit Username!</div><?php }
					if($_GET['e'] == 3) { ?><div class="alert alert-error">Your Minecraft username must be premium!</div><?php }
					if($_GET['e'] == 4) { ?><div class="alert alert-error">Your Bukkit username must be valid!</div><?php }
					if($_GET['e'] == 5) { ?><div class="alert alert-error">You don't have the minimum number of posts/plugins!</div><?php }
				?>
				<form class="applyForm" action="applyScript.php" method="POST">
					<fieldset>
						<legend>Apply</legend>
						<label>Username: </label>
						<input type="text" name="minecraft" placeholder="Your username in Minecraft" />
						<label>Bukkit Name:</label>
						<input type="text" name="bukkit" placeholder="Your username on Bukkit" />
						</br>
						<button type="submit" class="btn btn-success">Submit</button>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>
