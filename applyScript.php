<?php
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
		return (int) $json[$id]['project_count'];
	}
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
	if($posts < 800) {
		header('Location: apply.php?e=5');
		die();
	}
	$plugins = getPluginCount($bukkit,getID($bukkit));
	if($plugins < 2) {
		header('Location: apply.php?e=5');
		die();
	}
	die('Success so far');
	
	
?>