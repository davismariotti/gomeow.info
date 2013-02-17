<?php
	function checkMinecraftPremium($user) {
		return file_get_contents('http://minecraft.net/haspaid.jsp?user='.$user);
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