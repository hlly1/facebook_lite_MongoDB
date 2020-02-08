<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

$sender = $_POST['senderAccept'];
$receiver = $_SESSION['email'];

updateApplyStatus($sender, $receiver);
insertFriend($sender, $receiver);
header("Location: home.php");





?>