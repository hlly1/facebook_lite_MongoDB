<?php
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

$senderEmail = $_POST['useraEmail'];
$receiverEmail = $_POST['userbEmail'];

$userExist = userExistCheck($receiverEmail);
$applyCheck = applyCheck($senderEmail, $receiverEmail);

if($userExist && $senderEmail != $receiverEmail){

    if(!$applyCheck){
        insertApply($senderEmail, $receiverEmail);
        header("Location: home.php");
    }else{
        header("Location: applyFail.php");
    }
}else{
    header("Location: applyFail.php");
}

?>