<?php
session_start();
$con=mysqli_connect("localhost","root","","video_player3");
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/video-player3/');
define('SITE_PATH','http://localhost/video-player3/');

?>