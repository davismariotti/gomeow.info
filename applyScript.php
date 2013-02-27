<?php
	include("../private/config.php");

	function connectDB($user, $pass, $db) {
		try {	
			return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
		} catch(PDOException $ex) {
			return $ex;
		}
	}
	function sendPM($recipients, $title, $message) {

		$post = array(
		'cmd' => 'login',
		'username' => $bktUsername,
		'password' => $bktPassword);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$store = curl_exec($ch);
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		$content = curl_exec($ch);
		curl_close($ch); 
		
		$post = array(
		'cmd' => 'start_conversation',
		'recipients' => $recipients,
		'title' => $title,
		'message' => $message,
		'd' => 1);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$store = curl_exec($ch);
		curl_setopt($ch, CURLOPT_URL, 'http://forums.bukkit.org/forumrunner/request.php');
		$content = curl_exec($ch);
		curl_close($ch); 
		}
		$content = curl_exec ($ch);
		curl_close ($ch); 
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
		$key = uniqid();
		$sql = "INSERT INTO `Applications`(`Minecraft`, `Bukkit`, `Posts`, `Plugins`,`Approved`,`Key`,`Activated`) VALUES (:mc,:bk,:posts,:plugins,0,:key,0)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':mc', $minecraft);
		$stmt->bindParam(':bk', $bukkit);
		$stmt->bindParam(':posts', $posts);
		$stmt->bindParam(':plugins', $plugins);
		$stmt->bindParam(':key', $key);
		$stmt->execute();
		
		$title = "BukkThat Verification";
		$message = "[b]BukkThat Verification[/b]\n\nYour key: ".$key."\n\nOr visit this link:\nhttp://gomeow.info/confirm.php?name=".$minecraft."&key=".$key;
		$message .= "\n\nIf this was an error, please contact gomeow";
		
		sendPM($bukkit, $title, $message);
		header('Location: apply.php?s=1');
		die();
	}
	else {
		$mc = $_POST['minecraft'];
		$key = $_POST['key'];
		
		if($mc=="") {
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
		
		$sql = "SELECT * FROM `Applications` WHERE `Minecraft` = :mc LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':mc', $mc);
		$stmt->execute();
		if($stmt->rowCount() == 0) {
			header('Location: confirm.php?e=3');
			die('Not applied');
		}
		$row = $stmt->fetch();
		$sql = "SELECT * FROM `Applications` WHERE `Minecraft` = :mc AND `Activated` = 0 LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':mc', $mc);
		$stmt->execute();
		if($stmt->rowCount() == 0) {
			header('Location: confirm.php?e=4');
			die('Already activated.');
		}
		if($row['Key']!=$key) {
			header('Location: confirm.php?e=5');
			die('Wrong key.');
		}
		
		$sql = "UPDATE `Applications` SET `Activated` = 1 WHERE `Minecraft` = :mc LIMIT 1"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':mc', $mc);
		$stmt->execute();
		
		$bukkit = $row['Bukkit'];
		$title = "BukkThat Acceptance";
		$message=""; //Add message here
		sendPM($bukkit, $title, $message);
		
		header('Location: confirm.php?s=1');
		die();
		
	}
?>
