<?php
require 'tools.php';
session_start();
$userInfo = getUserInfo($_SESSION['email']);

?>
<html>
<header class="navbar navbar-dark navColor">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>Facebook-Lite | Assignment 1</title>
<link href="css/custom.css" rel="stylesheet">

      <div class="container">
        <a class="navTitle" href="home.php">facebook</a>
      </div>

			<!-- Nav Bar -->

</header>

<body>

<div class="container jumbotron text-center">
	<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
	<h1>Update my Profile</h1><br><br>
	
	<form action="updateProfile.php" method="POST" id = "updateUser">
		<label> *Screen Name (required): </label><br><input type="text" name="sname" class="form-control" value = "<?php echo $userInfo->s_name;?>" form = "updateUser" required/><br>
		<label> Status: </label><br><input type="text" name="status" class="form-control" value = "default..." form = "updateUser" required><br>
		<label> Location: </label><br><input type="text" name="location" class="form-control" form = "updateUser" value="<?php echo $userInfo->location;?>"><br>
		<label> Who can see my posts: </label><br>
			<select class="form-control" name="v_lv" form = "updateUser">
			  <option value="0">Everyone</option>
			  <option value="1">Only Friends</option>
			  <option value="2">Only me</option>
			</select><br>
		<input type="submit" class="btn btn-primary" value="Update my Profile" form = "updateUser">
	</form>


	
	<a href="home.php">Go back to homepage</a>
	<br>
	<br>

	</div>
	</div>
</div>


</body>
</html>