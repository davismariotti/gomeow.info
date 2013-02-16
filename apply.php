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
				<ul>
					<li>post amount goes here posts</li>
					<li>plugin amount goes here plugins</li>
					<li>donation amount goes here amount</li>
				</ul>
				<form class="applyForm" action="applyScript.php" method="POST">
					<fieldset>
						<legend>Apply</legend>
						<label>Username: </label>
						<input type="text" placeholder="Minecraft Username" />
						<label>Link to Bukkit Profile:</label>
						<input type="text" placeholder="Bukkit Link" />
						</br>
						<button type="submit" class="btn btn-success">Submit</button>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>
