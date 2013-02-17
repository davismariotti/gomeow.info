<?php
	error_reporting(0);

	function checkMinecraftPremium($user) {
		return file_get_contents('http://minecraft.net/haspaid.jsp?user='.$user);
	}
	
	function checkPosts($user) {
	    $baseURL = "http://forums.bukkit.org/mobiquo/mobiquo.php";
	    $method = "get_user_info";
	    $username = base64_encode("lol768");
	    $postString = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><methodCall><methodName>get_user_info</methodName><params><param><value><base64>$username</base64></value></param></params></methodCall>";
	    $options = array('http' => array('method'  => 'POST','content' => $postString));
	    $context  = stream_context_create($options);
	    $result = file_get_contents($baseURL, false, $context);
	    $sp = strpos($result, "<member><name>post_count</name>
	    <value><int>");
	    $sp = $sp + 44;
	    $val = substr($result, $sp, 12);
	    $val = (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
	    return $val;
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
	 die("Has paid");
	
?>
