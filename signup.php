<?php
require('tools.php');
if (!empty($_SESSION['email']) && isset($_SESSION['email'])){
	header("Location: home.php");
}
$signError = '';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['fname']) && !empty($_POST['sname']) && !empty($_POST['dob'])){
		if(userExistCheck($_POST['email'])){
			$signError = "*You have already registered the account, please login directly!";
		}else{
				insertUser(
					$_POST['email'], 
					$_POST['password'], 
					$_POST['fname'], 
					$_POST['sname'], 
					$_POST['gender'], 
					$_POST['address'], 
					$_POST['dob']
				);
				header("Location: index.php");
			}
		}else{
			$signError = "*Warning: You must fill all blanks with '*' mark!";
		}
		
}



?>


<html>
<header class="navbar navbar-dark navColor">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>Facebook-Lite | Assignment 1</title>
<link href="css/custom.css" rel="stylesheet">

      <div class="container">
        <a class="navTitle" href="index.php">facebook</a>
      </div>

			<!-- Nav Bar -->

</header>

<body>

<div class="container jumbotron text-center">
	<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
	<h1>Register</h1><br><br>
	<p style = 'color:red;font-weight: bold;'><?php echo $signError; ?></p><br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<label> *Email (required): </label><br><input type="email" name="email" class="form-control"/><br>
		<label> *Password (required): </label><br><input type="password" name="password" class="form-control"/><br>
		<label> *Real Name (required): </label><br><input type="text" name="fname" class="form-control"/><br>
		<label> *Screen Name (required): </label><br><input type="text" name="sname" class="form-control"/><br>
		<label> Gender: </label><br>
			<select class="form-control" name="gender">
			  <option value="Mystery">Mystery</option>
			  <option value="Female">Female</option>
			  <option value="Male">Male</option>
			</select><br>
		<label> Address: </label><br><input type="text" name="address" class="form-control"/><br>
		<label> Date of Birth: </label><br><input type="date" name="dob" class="form-control"/><br><br>
		<input type="submit" class="btn btn-primary" value="Register">
	</form>
	<a href="index.php">Already have a account?</a>
	</div>
	</div>
</div>


</body>
</html>