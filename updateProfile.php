<?php
require 'tools.php';
session_start();
$email = $_SESSION['email'];
$sname = $_POST['sname'];
$status = $_POST['status'];
$location = $_POST['location'];
$v_lv = (int)$_POST['v_lv'];

updateDetail($email, $sname, $status, $location, $v_lv);
header('Location: home.php');

?>