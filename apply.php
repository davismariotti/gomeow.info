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
						<legend>Apply</legend>
						<label>Username: </label>
						<input type="text" placeholder="Minecraft Username" />
						<label>Link to Bukkit Profile:</label>
						<input type="text" placeholder="Bukkit Link" />
						<label>Now write a paragraph about how you won't grief</label>
						<textarea></textarea>
						<button type="submit" class="btn btn-success">Submit</button>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>
