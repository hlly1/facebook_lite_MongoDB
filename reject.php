<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}
$sender = $_POST['senderReject'];
$receiver = $_SESSION['email'];
deleteApply($sender, $receiver);
header("Location: home.php");

?>