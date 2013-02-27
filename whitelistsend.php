<?php
	require('../private/config.php');
	
	function sendPM($recipients, $title, $message) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "cmd=login&username=".$bktUsername."&password=".$bktPassword);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$store = curl_exec ($ch);
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		$content = curl_exec ($ch);
		curl_close ($ch); 
		
		$post = array(
		'cmd' => 'start_conversation',
		'recipients' => $recipients,
		'title' => $title,
		'message' => $message,
		'd' => 1);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$store = curl_exec ($ch);
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		$content = curl_exec ($ch);
		curl_close ($ch); 
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
	define( 'MQ_TIMEOUT', 5 );
	
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
		
		echo HTMLSpecialChars($Data);
	}
	catch( MinecraftRconException $e )
	{
		header('Location: approve.php?pw='.$whitelistpassword/'&e=1');
		die('Error');
	}
	$Rcon->Disconnect();
	
	$sql = "UPDATE `Applications` SET `Approved` = 1 WHERE `Minecraft` = :user";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':user', $user);
	$stmt->execute();
	$title = "BukkThat Approval";
	$message = "You were accepted!\n\n";
	$message .= "You can now log on to play.bukkthat.com\n\n";
	$message .= "See you there!";
	
	sendPm($user, $title, $message);
	header('Location: approve.php?pw='.$whitelistpassword);
?>