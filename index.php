<?php
require('tools.php');
$loginFail = '';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(login($_POST['email'], md5($_POST['password']))){
        session_start();
        $_SESSION['email'] = $_POST['email'];
		header("Location: home.php");
	}else{
		$loginFail = "*Invalid Email or Password";
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
    <h1>Login</h1><br><p style = 'color:red;'><?php echo $loginFail; ?></p><br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <label> Email: </label><br><input type="email" name="email" class="form-control" required/><br>
    <label> Password: </label><br><input type="password" name="password" class="form-control" required/><br><br>

    <input type="submit" class="btn btn-primary" value="Login">
        <a href="signup.php">Not registered?</a>    
    </form>
    </div>
    </div>

</div>


</body>
</html>



