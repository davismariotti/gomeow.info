<?php
	require("functions.php");
	include("../private/config.php");
	
	function connectDB($user, $pass, $db) {
		try {	
			return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
		} catch(PDOException $ex) {
			return $ex;
		}
	}

	function getClientIP () {
	 if (isset ($_SERVER['HTTP_X_FORWARDED_FOR'])){
	  $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	 }
	 elseif (isset ($_SERVER['HTTP_X_REAL_IP'])){
	  $clientIP = $_SERVER['HTTP_X_REAL_IP'];
	 }
	 else {
	  $clientIP = $_SERVER['REMOTE_ADDR'];
	 }
	 return $clientIP;
	}
	
	$visits = 1;
	$db = connectDB($dbUser, $dbPass, $dbName);
	if ($db instanceof PDOException) {
		die ($db->getMessage());
	}
	$ip = getClientIP();
	$sql = "SELECT * FROM `IPS` WHERE `IP` = :ip LIMIT 1"; 
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ip', $ip);
	$stmt->execute();
	if($stmt->rowCount() == 0) {
		$sql = "INSERT INTO `IPS`(`IP`, `Visits`) VALUES (:ip, 1)"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':ip', $ip);
		$stmt->execute();
		
		$sql = "SELECT * FROM `Stats` WHERE 1"; 
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch();
		$TotalHits = $row['TotalHits'];
		$TotalHits = $TotalHits + 1;
		$TotalUniqueVisits = $row['TotalUniqueVisits'];
		$TotalUniqueVisits = $TotalUniqueVisits + 1;
		
		$sql = "UPDATE `Stats` SET `TotalHits` = :value1 , `TotalUniqueVisits` =  :value2 WHERE 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':value1', $TotalHits);
		$stmt->bindParam(':value2', $TotalUniqueVisits);
		$stmt->execute();
	}
	else {
		$sql = "SELECT * FROM `IPS` WHERE `IP` = :ip LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':ip', $ip);
		$stmt->execute();
		$row = $stmt->fetch();
		$visits = $row['Visits'];
		$visits = $visits + 1;
		
		$sql = "UPDATE `IPS` SET `Visits` = :visits WHERE `IP` = :ip"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':ip', $ip);
		$stmt->bindParam(':visits', $visits);
		$stmt->execute();
		
		$sql = "SELECT * FROM `Stats` WHERE 1"; 
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch();
		$TotalHits = $row['TotalHits'];
		$TotalHits = $TotalHits + 1;
		$TotalUniqueVisits = $row['TotalUniqueVisits'];
		
		$sql = "UPDATE `Stats` SET `TotalHits` = :value WHERE 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':value', $TotalHits);
		$stmt->execute();
		
	}
?>
<html>
	<head>
		<title>GOMEOW</title>
		<?php headIncludes(); ?>
	</head>
	<body>
		<?php navBar(); ?>
		<div class="container">
			<div class="well mainContent">
			<img class="thumbnail img-gomeow" src = "files/90728305.jpg" width = "180" height = "180" />

				<h1>GOMEOW!</h1>
				<p>I am gomeow. Inspector gomeow.</p>
				<p>You have visited me <?php echo $visits ?> times!</p>
				<p>This site has had <?php echo $TotalHits; ?> total hits with <?php echo $TotalUniqueVisits; ?> unique visits!</p>
			</div>
		</div>
	</body>
</html>
