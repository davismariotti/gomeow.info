<!DOCTYPE html>
<?php
	include('../private/config.php');
	require("functions.php");
	$auth = false;
	if($_GET['pw']==$whitelistpassword) {
		$auth = true;
	}
	function connectDB($user, $pass, $db) {
		try {	
			return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
		} catch(PDOException $ex) {
			return $ex;
		}
	}
	$db = connectDB($dbUser, $dbPass, $dbName);
	if ($db instanceof PDOException) {
		die ($db->getMessage());
	}
	if($_GET['showapproved']=='true') {
		$sql = "SELECT * FROM `Applications`";
	}
	else {
		$sql = "SELECT * FROM `Applications` WHERE `Approved` = 0 AND `Activated` = 1";
	}
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$rows = $stmt->fetchAll();
	
?>
<html>
	<head>
		<title>Approve</title>
		<?php headIncludes(); ?>
	</head>
	<body>
		<?php navBar(); ?>
		<div class="container">
			<div class="well mainContent">
			<?php if(!$auth) { ?><div style='text-align:center;'>You are not allowed to view this page!</div><?php } else {?>
				<table class="table table-striped table-hover">
					<caption>Applications</caption>
					<tr style="font-weight: bold;">
        				<th>Minecraft</th>
        				<th>Bukkit</th>
                    	<th>Posts</th>
                    	<th>Plugins</th>
                    	<th>Approve?</th>
     		 		</tr>
     		 		<?php
     		 			if($stmt->rowCount()!=0) {
     		 				foreach($rows as $row) {
								?>
								<tr>
									<td><?php echo $row['Minecraft']; ?></td>
									<td><?php echo $row['Bukkit']; ?></td>
									<td><?php echo $row['Posts']; ?></td>
									<td><?php echo $row['Plugins']; ?></td>
									<td><a class='btn btn-primary' href='whitelistsend.php?user=<?php echo($row['Minecraft']); ?>&pw=<?php echo($whitelistpassword); ?>'>Yes</a></td>
								</tr>
								<?php
							}
     		 			}
     		 		?>
				</table>
				<?php if($stmt->rowCount()==0) { ?>
					<div style='text-align:center;'>
						No applications waiting.
					</div>
				<?php }
				} ?>
			</div>
		</div>
	</body>
</html>
