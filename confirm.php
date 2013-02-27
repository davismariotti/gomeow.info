<!DOCTYPE html>
<?php
	require("functions.php");
	$key = 	$_GET['key'];
	$name = $_GET['name'];
?>
<html>
	<head>
		<title>Confirm</title>
		<?php headIncludes(); ?>
	</head>
	<body>
		<?php navBar(); ?>
		<div class="container">
			<div class="well mainContent">
				<div style='text-align: center;'>
					<img src="http://forums.bukkit.org/logo.png"></img>
					<h3>Community Server</h6>
				</div>
				<?php 
					if($_GET['e'] == 1) { ?><div class="alert alert-error">You need to fill in your Minecraft Username!</div><?php }
					if($_GET['e'] == 2) { ?><div class="alert alert-error">You need to fill in your Key!</div><?php }
					if($_GET['e'] == 3) { ?><div class="alert alert-error">You never applied!</div><?php }
					if($_GET['e'] == 4) { ?><div class="alert alert-error">Your application has already been activated!</div><?php }
					if($_GET['e'] == 5) { ?><div class="alert alert-error">Your key was not correct!</div><?php }
					if($_GET['s'] == 1) { ?><div class="alert alert-success">Success! Now just wait for an admin to approve your application and you will be sent a provate message as a success message.</div><?php }
				?>
				<form class="applyForm" action="applyScript.php" method="POST">
					<fieldset>
						<legend>Confirm</legend>
						<label>Bukkit Name:</label>
						<input type="text" name="minecraft" value=<?php echo("\"".$name."\""); ?> placeholder="Your username in Minecraft" />
						<label>Confirm Key:</label>
						<input type="text" name="key" <?php echo("value=\"".$key."\""); ?>placeholder="Your activation key" />
						<input type="hidden" name="method" value="confirm" />
						</br>
						<button type="submit" class="btn btn-success">Submit</button>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>
