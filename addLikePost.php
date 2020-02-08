<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

$user = $_POST['postLikeUserID'];
$post = $_POST['postIDlike'];

// echo "<p>".$user."</p><br><br>";
// echo "<p>".$post."</p><br><br>";
insertLikePost($post, $user);
header("Location: home.php");
?>