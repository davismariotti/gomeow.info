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
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>
