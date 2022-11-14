<?php

/*
=============================================
manages user page

=============================================
*/

session_start();
$pageTitle ='members';
	if(isset($_SESSION['Username'])){

	
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 
		


		 ?>

	

	    
		<?php 
		if ($do == 'Edit') {//edit page  
			// check if get request userid is numirec and get the integer value of it
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			//selecct all data depend this id
			$stmt = $con->prepare("SELECT * FROM users WHERE  UserID = ? LIMIT 1");
			//exexute query
			$stmt->execute(array($userid));
			//fetch the data
			$row =$stmt->fetch();
			// the row count
			$count = $stmt->rowCount();
			// if there is such id show the form
			if ($count> 0) { ?>

				<h1 class="text-center">Edit user</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $userid ?>">
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete ="off" required="required">
							</div>
						</div>
						
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-4">
								<input type="email" name="Email" class="form-control" value="<?php echo $row['Email'] ?>" required="required">
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" name="Full" class="form-control" value="<?php echo $row['FullName'] ?>" required="required">
							</div>
						</div>
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="save" class="btn btn-primary">
							</div>
						</div>
				</form>
			</div>



		

		<?php 
				// else  there is no id show err message
				}else{
					echo "<div class='container'>";
					$theMsg='<div class ="alert alert-danger"> there is no such id</div>';
					redirectHome($theMsg);
					echo "</div>";
				}
		}	

		elseif ($do == 'Update') {// update page
			echo "<h1 class='text-center'>Update Member</h1>";
			echo "<div class ='container'>";
 
			if ($_SERVER['REQUEST_METHOD']=='POST') {
				//get the variable from the form
				$id = $_POST['userid'];
				$user = $_POST['username'];
				$email = $_POST['Email'];
				$name = $_POST['Full'];

				//password triks

			
				// validate the form
				$formErrors = array();
				if (strlen($user)<4) {

					$formErrors[]='<div class="alert alert-danger">username cant be less than 4 carcaters</div>';
				}
				if (strlen($user)>20) {

					$formErrors[]='<div class="alert alert-danger"> username cant be more than 4 carcaters</div>';
				}
				if (empty($user)) {
					$formErrors[]=' <div class="alert alert-danger"> username cant be empty</div>';
				}

				if (empty($name)) {
					$formErrors[]=' <div class="alert alert-danger"> name cant be empty</div>';
				}
				if (empty($email)) {
					$formErrors[]='<div class="alert alert-danger"> email cant be empty</div>';
				}

				foreach ($formErrors as $error) {
					echo $error ;
				}
				// check if there is no error proced the update operation 
				if(empty($formErrors)) {
  					$stmt2=$con -> prepare("SELECT * FROM users WHERE Username =? AND UserID !=? ");
  					$stmt2-> execute(array($user,$id));
  					$count =$stmt2 -> rowCount(); 
  					
  					if ($count == 1) {
  						echo "<div class='alert alert-danger'>sorry this user is exist</div>";
  						redirectHome($theMsg,'back');
  					} else {
  							// update the database with this is info

							$stmt =$con->prepare("UPDATE users SET Username = ?,Email =?, FullName=? WHERE UserID = ?");
							 $stmt->execute(array($user,$email,$name,$id));

							// echo succes message

							 $theMsg= "<div class='alert alert-success'>". $stmt->rowCount() . 'record updated</div>';
							 redirectHome($theMsg,'back');

  					}


				}

			}else{

				$theMsg= '<div class="alert alert-danger">SORRY YOU CANT BROWSE THIS PAGE DIRECT </div>';
				redirectHome($theMsg);
			}
			echo "</div>";
		}

		
		include $tpl . 'footer.php'; 

	}
	else{
		
		header('Location:index.php');
		exit();
	} 