<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

$array = array("", "");
$array = explode("+",$_POST['postID2']);

$postID = $array[1];
$replyer = $_SESSION['email'];
$parent = $array[0];
$content = $_POST['newCommentComment'];
$poster = getReplyerToPoster($parent);
// var_dump($poster);
echo "<p>".$poster."</p><br><br>";
// echo "<p>".$postID."</p><br><br>";
// echo "<p>".$parent."</p><br><br>";
// echo "<p>".$content."</p><br><br>";
insertReplyII($poster, $replyer, $postID, $content, $parent);
header("Location: home.php");










?>