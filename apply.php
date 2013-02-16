<?php
	require("functions.php");
?>

<html>
	<head>
		<title>GOMEOW</title>
		<?php headIncludes(); ?>
	</head>
	<body>
		<?php navBar(); ?>
		<div class="container">
			<div class="well mainContent applyForm">
				<form action="applyScript.php" method="POST">
					<fieldset>
						<img src="http://forums.bukkit.org/logo.png"></img>
						<h3>Community Server</h6>
						<h6>Conditions:<h6>
						<ul>
							<li>post amount goes here posts</li>
							<li>plugin amount goes here plugins</li>
							<li>donation amount goes here amount</li>
						</ul>
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
