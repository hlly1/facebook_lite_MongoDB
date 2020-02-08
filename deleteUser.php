<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

//get user email
$userDelete = $_SESSION['email'];

//delete all likes of this user
deleteLikes($userDelete);

//delete all friend apply of this user
deleteUserApply($userDelete);

//delete all friendships of this user
deleteFriendship($userDelete);

//delete all posts of this user
deletePosts($userDelete);

//delete all replys of this user
deleteReplys($userDelete);

//delete this user
deleteUser($userDelete);

//close session
session_destroy();

//jump to login page
header("Location: index.php");




?>