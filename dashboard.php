<?php
	ob_start(); // output buffering start  
	session_start();
	if(isset($_SESSION['Username'])){

		$pageTitle = 'Dashboard';
		include 'init.php';
		
		/* start dashboard page*/
		$numUsers = 6; // number of latest users
		$latestUsers =getLatest("*","users","UserID",$numUsers);// latest user array
		$userkey=$_SESSION['ID'];
		

//

		?>
	<div class="home-stats">
		<div class="container text-center">
			<h1>Dashboard</h1>

			<div class="row">
				<div class="col-md-4">
					<div class="stat st-members ">
					<i class="fa fa-users"></i>
						<div class="info">
							<b>Total Users</b>
						<span><?php echo countItems('UserID','users',) ?></span>
						</div>

					</div>
				</div>	
				<div class="col-md-4">
					<div class="stat st-members " style="background-color: #e13624;">
					<i class="fa fa-spinner"></i>
						<div class="info">
							<b>Total todos in progress</b>
						<span><?php echo countCatUnDone('ID','todos',$userkey) ?></span>
						</div>

					</div>
				</div>	
				

				
				<div class="col-md-4">
					<div class="stat st-comments" style="background-color: #24e124;">
					<i class="fa fa-check"></i>
					<div class="info">					
					<b>Total To-do Done</b>
					<span><a href="todos.php"><?php echo countCatDone('ID','todos',$userkey) ?></a></span>
					</div>
					</div>
				</div>
			</div>
			
		</div>
		</div>
	
    <div class="latest">
		<div class="container"> 
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
					<?php  ;?>
						<div class="panel-heading">
							<i class="fa fa-users"></i> Latest <?php echo $numUsers  ?>  users
							<span class="toggle-info pull-right">
								<i class="fa fa-plus fa-lg"></i>
							</span>
						</div>	
						<div class="panel-body">
							<ul class="list-unstyled latest-users">

							<?php 
								
								foreach ($latestUsers as $user) {

								echo '<li>' . $user['Username'] .'</i>';
							

							
							;
								}

							?>
							</ul>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>

	</div>	
		<?php
		/* start dashboard page*/
		include $tpl . 'footer.php'; 

	}else{
		
		header('Location:index.php');
		exit();
	} 

	ob_end_flush();
	?>