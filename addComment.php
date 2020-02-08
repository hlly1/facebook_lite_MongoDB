<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

$postID = $_POST['postID'];
$poster = getPostUser($postID);
$replyer = $_SESSION['email'];
$content = $_POST['newComment'];

// echo "<p>".$postID."</p><br><br>";
// echo "<p>".$poster->email."</p><br><br>";
// echo "<p>".$replyer."</p><br><br>";
// echo "<p>".$content."</p><br><br>";

insertReply($poster->email, $replyer, $postID, $content);


header("Location: home.php");

?>