<?php
include("../private/config.php");
$ip = $_SERVER['REMOTE_ADDR'];
$auth = false;
error_reporting(E_ERROR);
//Only allow github/staff to build
if ($ip == "207.97.227.253" || $ip == "50.57.128.197" || $ip == "108.171.174.178" || $ip == "76.126.30.120" || $ip == "50.57.231.61") {
$auth = true;
}
if($_GET['pw'] == $buildPW) {
	$auth = true;
}
if (!$auth) {
file_put_contents("logs/gitlog.txt", "\n" . date("Y-m-d H:i:s") . ": Unauthorised deploy attempt from $ip", FILE_APPEND | LOCK_EX);
die("Not authorised.");
}
function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}
$image = file_get_contents("https://github.com/gomeow/gomeow.info/archive/master.zip");
file_put_contents("master.zip", $image);
$zip = new ZipArchive;
if ($zip->open('master.zip') === TRUE) {
    $zip->extractTo(getcwd());
    $zip->close();
    recurse_copy(getcwd() . "/gomeow.info-master/", getcwd());
    unlink("master.zip");
    rmdir_recursive(getcwd() . "/gomeow.info-master/");
    file_put_contents("logs/gitlog.txt", "\n" . date("Y-m-d H:i:s") . ": Successful deploy from $ip", FILE_APPEND | LOCK_EX);   
} else {
    file_put_contents("logs/gitlog.txt", "\n" . date("Y-m-d H:i:s") . ": FAILED deploy from $ip", FILE_APPEND | LOCK_EX);
}
?>
