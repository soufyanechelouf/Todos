<?php

	session_start();
	$noNavbar='';
	$pageTitle="Login";

	if(isset($_SESSION['Username'])){
		header('Location:dashboard.php'); 
	}
	include 'init.php';
	// chek if user coming from request post
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (isset($_POST['login'])) {
	 	
$user = $_POST['user'];
$email = $_POST['email'];



//check if user in database
 
$stmt = $con->prepare("SELECT UserID,Username,Email FROM users WHERE Email=? ");
$stmt->execute(array($email));
$row =$stmt->fetch();
$count = $stmt->rowCount();
if ($count>0){

	$_SESSION['Username'] = $row['Username'];
	 // register session name
	$_SESSION['ID'] = $row['UserID'];

	
	header('Location:dashboard.php');//redirectio to dashboard
	exit();
}
} else {
	$formErrors = array();

	$username = $_POST['username'];
	$email = $_POST['email'];

	if (isset($username)) {
		$filtredUser =filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		if (strlen($filtredUser)<4 ) {
			$formErrors[] ='Username must be larger than 4 caracter';
		}
	}


	if (isset($email)) {
		$filtredEmail =filter_var($email, FILTER_SANITIZE_EMAIL);
		if (filter_var($filtredEmail,FILTER_VALIDATE_EMAIL)!= true) {
			
			$formErrors[]= 'this email is not valid';
		}
	}

		// check if there is no error proced the user add 
	    if(empty($formErrors)) {

					//check if user exist in DB
					
			    $check =checkItem("Username","users",$username);
			    $check1=checkItem("Email","users",$email);
			    if ($check == 1) {
			    	$formErrors [] = 'this user is exist';}	
			    elseif($check1 == 1){
			    	$formErrors [] = 'this email is exist';
			    }else{

					// insert userinfo in data base
					$stmt = $con ->prepare("INSERT INTO users(Username,Email,RegStatus,Date)VALUES(:zuser,:zmail,1,now()) ");
					$stmt->execute(array(
						'zuser'=>$username,
						
						'zmail'=>$email
						
						));


				// echo succes message

				 $succesMsg='congrate you are now registred user';


				}    
			}

}
} 


?>
	<div class="container login-page">
	<h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>

		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method ="POST" >
			<input class="form-control" type="text" name="email" autocomplete="off" placeholder="type your email">
			
			<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
		
		</form>
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method ="POST">
			<input pattern=".{4,}" title="Username must be larger than 4" class="form-control" type="text" name="username" autocomplete="off" placeholder="type your user name" required>
	
			<input class="form-control" type="email" name="email" placeholder="type a valid email" required>


			<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup"></div>
		
		</form>
		<div class="the-errors text-center">
			<?php 
				if (!empty($formErrors)) {
					foreach ($formErrors as $error) {
						echo '<div class="msg error">'. $error ."</div>";
					}
				}
				if (isset($succesMsg)) {
					echo '<div class="msg success">'.$succesMsg .'</div>';
				}
			?>
			
		</div>
    </div>



<?php
	include $tpl .'footer.php';
