<?php

	require('../private/config.php');
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


	if(!isset($_GET['user']) || $_GET['user']=='') {
		die('User undefined.');
	}
	if(!isset($_GET['pw']) || $_GET['pw']=='') {
		die('Not Authorised.');
	}
	if($_GET['pw']!=$whitelistpassword) {
		die('Not Authorised.');
	}
	
	
	
	$user = $_GET['user'];
	
	define( 'MQ_SERVER_ADDR', '216.244.83.139' );
	define( 'MQ_SERVER_PORT', 25605 );
	define( 'MQ_SERVER_PASS', 'passwordtest' );
	define( 'MQ_TIMEOUT', 2 );
	
	require 'MinecraftQuery/MinecraftRcon.class.php';
	
	try
	{
		$Rcon = new MinecraftRcon;
		
		$Rcon->Connect( MQ_SERVER_ADDR, MQ_SERVER_PORT, MQ_SERVER_PASS, MQ_TIMEOUT );
		
		$Data = $Rcon->Command("whitelist add ".$user);
		
		if($Data===false) {
			throw new MinecraftRconException("Failed to get command result.");
		}
		else if(StrLen($Data)==0) {
			throw new MinecraftRconException("Got command result, but it's empty.");
		}
		
		//echo HTMLSpecialChars($Data);
	}
	catch( MinecraftRconException $e )
	{
		header('Location: approve.php?pw='.$whitelistpassword);
		die('Error');
	}
	$Rcon->Disconnect();
	
	$sql = "UPDATE `Applications` SET `Approved` = 1 WHERE `Minecraft` = :user";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':user', $user);
	$stmt->execute();
	header('Location: approve.php?pw='.$whitelistpassword);
?>