<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

$email = $_SESSION['email'];
$content = $_POST['post'];

insertPost($email, $content);
header("Location: home.php");



?>