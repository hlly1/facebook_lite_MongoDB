<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}
//user email
$user = $_POST['commLikeUserID'];
//sub reply timestamp
$reply = $_POST['commIDlike'];
//parent post timestamp
$parentID = $_POST['parentID'];

echo "<p>".$user."</p><br><br>";
echo "<p>".$reply."</p><br><br>";
echo "<p>".$parentID."</p><br><br>";

//Insert like-reply document
insertLikeReply($reply, $user, $parentID);
//get num of like then update
$newlikeNum = getLikeReplyNum($reply) + 1;
echo $newlikeNum;
//update sub document of reply array
// updateReplyLike($parentID, $reply, $newlikeNum);


header("Location: home.php");



?>