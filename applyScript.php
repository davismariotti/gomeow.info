<?php
	//require("functions.php");
	include("../private/config.php");

	function connectDB($user, $pass, $db) {
		try {	
			return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
		} catch(PDOException $ex) {
			return $ex;
		}
	}
	function sendPM($user,$message) {
	
	}
	function checkMinecraftPremium($user) {
		return file_get_contents('http://minecraft.net/haspaid.jsp?user='.$user);
	}
	function getPosts($user) {
		error_reporting(0);
		$baseURL = "http://forums.bukkit.org/mobiquo/mobiquo.php";
		$method = "get_user_info";
		$username = base64_encode($user);
		$postString = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><methodCall><methodName>get_user_info</methodName><params><param><value><base64>$username</base64></value></param></params></methodCall>";
		$options = array('http' => array('method'  => 'POST','content' => $postString));
		$context  = stream_context_create($options);
		$result = file_get_contents($baseURL, false, $context);
		$result = str_replace("</member>","",$result);
		$split = explode("<member>",$result);
		if(strpos($split[1],"result")!==false) {
			return null;
		}
		$end = str_replace("post_count","",$split[2]);
		$end = str_replace("<name></name>\n<value><int>","",$end);
		$end = str_replace("</int></value>","",$end);
		return intval($end);
	}
	function getID($user) {
		error_reporting(0);
		$baseURL = "http://forums.bukkit.org/mobiquo/mobiquo.php";
		$method = "get_user_info";
		$username = base64_encode($user);
		$postString = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><methodCall><methodName>get_user_info</methodName><params><param><value><base64>$username</base64></value></param></params></methodCall>";
		$options = array('http' => array('method'  => 'POST','content' => $postString));
		$context  = stream_context_create($options);
		$result = file_get_contents($baseURL, false, $context);
		$result = str_replace("</member>","",$result);
		$split = explode("<member>",$result);
		if(strpos($split[1],"result")!==false) {
			return "NO!";
		}
		$end = str_replace("user_id","",$split[1]);
		$end = str_replace("<name></name>\n<value><string>","",$end);
		$end = str_replace("</string></value>","",$end);
		return intval($end);
	}
	function getPluginCount($user,$id) {
		$url = 'http://forums.bukkit.org/members/'.$user.".".$id;
		$json = file_get_contents('http://tools.bukkit.org/associt/api/users_have_projects/?user_list='.$id);
		$json = json_decode($json, true);
		if(!$json[$id]['associated']) {
			return 0;
		}
		return (int) $json[$id]['project_count'];
	}
	if($_POST['method']!="confirm") {	
		$minecraft = $_POST['minecraft'];
		$bukkit = $_POST['bukkit'];
		if(!isset($bukkit) || is_null($bukkit) || $minecraft=='') {
			header('Location: apply.php?e=1');
			die();
		}
		if(!isset($minecraft) || is_null($minecraft) || $bukkit=='') {
			header('Location: apply.php?e=2');
			die();
		}
		
		if(checkMinecraftPremium($minecraft) == 'false') {
			header('Location: apply.php?e=3');
			die();
		}
		$posts = getPosts($bukkit);
		if(is_null($posts)) {
			header('Location: apply.php?e=4');
			die();
		}
		$minimum = false;
		if($posts > 799) {
			$minimum = true;
		}
		$plugins = getPluginCount($bukkit,getID($bukkit));
		if($plugins > 2 && $plugins !== false) {
			$minimum = true;
		}
		if($minimum = false) {
			header('Location: apply.php?e=5');
			die();
		}
		
		$db = connectDB($dbUser, $dbPass, $dbName);
		if ($db instanceof PDOException) {
			die ($db->getMessage());
		}
		
		$sql = "SELECT * FROM `Applications` WHERE `Minecraft` = :mc OR `Bukkit` = :bk LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':mc', $minecraft);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->execute();
		if($stmt->rowCount() != 0) {
			header('Location: apply.php?e=6');
			die('nope');
		}
		$sql = "INSERT INTO `Applications`(`Minecraft`, `Bukkit`, `Posts`, `Plugins`,`Approved`,`Key`,`Activated`) VALUES (:mc,:bk,:posts,:plugins,0,:key,0)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':mc', $minecraft);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->bindParam(':posts', $posts);
		$stmt->bindParam(':plugins', $plugins);
		$stmt->bindParam(':key', uniqid());
		$stmt->execute();
		
		//$message = "";
		
		//sendPM($bukkit,$message);
		header('Location: apply.php?s=1');
		die();
	}
	else {
		$bukkit = $_POST['bukkit'];
		$key = $_POST['key'];
		
		if($bukkit=="") {
			header('Location: confirm.php?e=1');
			die();
		}
		if($key=="") {
			header('Location: confirm.php?e=2');
			die();
		}
		
		$db = connectDB($dbUser, $dbPass, $dbName);
		if ($db instanceof PDOException) {
			die ($db->getMessage());
		}
		
		$sql = "SELECT * FROM `Applications` WHERE `Bukkit` = :bk LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->execute();
		if($stmt->rowCount() == 0) {
			header('Location: confirm.php?e=3');
			die('Not applied');
		}
		$sql = "SELECT * FROM `Applications` WHERE `Bukkit` = :bk AND `Activated` = 0 LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->execute();
		if($stmt->rowCount() == 0) {
			header('Location: confirm.php?e=4');
			die('Already activated.');
		}
		$sql = "SELECT * FROM `Applications` WHERE `Bukkit` = :bk LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->execute();
		$row = $stmt->fetch();
		
		if($row['Key']!=$key) {
			header('Location: confirm.php?e=5');
			die('Wrong key.');
		}
		
		$sql = "UPDATE `Applications` SET `Activated` = 1 WHERE `Bukkit` = :bk LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->execute();
		
		header('Location: confirm.php?s=1');
		die();
		
		$message="";
		sendPM($bukkit,$message);
	}
?>
