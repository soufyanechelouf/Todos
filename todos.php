<?php

/*
=============================================
todo page
=============================================
*/
//ob_start();
session_start();
$pageTitle ='Todos';
	if(isset($_SESSION['Username'])){

	
		include 'init.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 
		$userkey=$_SESSION['ID'];
		//start manage page
		if ($do =='Manage') {
			$sort='ASC';
			$sort_array=array('ASC','DESC');
			if(isset($_GET['sort'])&& in_array($_GET['sort'],$sort_array)){
				$sort=$_GET['sort'];
			}
			$stmt2 = $con ->prepare("SELECT * FROM todos WHERE  UserID = $userkey ORDER BY Ordering $sort ")	;	
			$stmt2-> execute();	 
			$cats = $stmt2->fetchAll(); ?>

			<h1 class="text-center">Manage Todos </h1>
			<div class="container todo">
				<a style="margin-bottom: 30px; margin-top: -10px;" class="add-todo btn btn-primary" href="todos.php?do=Add"><i class="fa fa-plus"></i> Add New TODO</a>
				<div class="panel panel-default">
					<div class="panel-heading">Manage Todos
						<div class="Ordering pull-right">
							Ordering:
							<a href="?sort=ASC">ASC</a> |
							<a href="?sort=DESC">DESC</a>
						</div>
					</div>
					<div class="panel-body " style="padding: 0px;">
					<?php  
					foreach($cats as $cat ){
						if(!$cat['Visibility']){
												echo '<div class="cat" style="padding: 15px;
	position: relative; color:gray">';
						}else{
						echo '<div class="cat" style="padding: 15px;
	position: relative;">';}
							echo "<div class ='hidden-buttons' style='position:absolute;
							top:15px;right: 10px;'>";
							if($cat['Visibility']){
												echo "<a href='todos.php?do=MakeDone&catid=". $cat['ID'] ."' class='btn btn-xs btn-success' style='
    margin-right:  5px;'><i class='fa fa-check'></i> Done</a>";
						}else{
						echo "<a href='todos.php?do=MakeUnDone&catid=". $cat['ID'] ."' class='btn btn-xs btn-Secondary' style='
    margin-right:  5px;'><i class='fa fa-cross'></i> Uncheck</a>";}
							echo "<a href='todos.php?do=Up&catid=". $cat['ID'] ."' class='btn btn-xs btn-success' style='
    margin-right:  5px;'><i class='fa fa-arrow-up'></i> Up</a>";
							echo "<a href='todos.php?do=Down&catid=". $cat['ID'] ."' class='btn btn-xs btn-warning' style='
    margin-right:  5px;'><i class='fa fa-arrow-down'></i> Down</a>";
								echo "<a href='todos.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-xs btn-primary' style='
    margin-right:  5px;'><i class='fa fa-edit'></i> Edit</a>";
								echo "<a href='todos.php?do=Delete&catid=". $cat['ID'] ."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
								echo "</div>";
							echo "<h3>" .$cat['Name'] ."</h3>";
							echo "<p>";if ($cat['Description'] =='') {
								echo 'this todo has no description';
							} else{ echo $cat['Description'];} 
							echo "</p>";
							echo "<p>";if ($cat['Due'] =='') {
								echo 'this todo has no due date';
							} else{ echo '<span class="visibility"> Due date </span><b>'. $cat['Due'].'</b>';} 
							echo "</p>";
							if ($cat['Visibility'] == 1) {
							 	echo '<span class="visibility"> Status: <b>  En cours</b> </span>';
							 } else{
							 	echo '<span class="visibility"> Status:<b>  Done</b> </span>';
							 }
							 
							
							
						
						echo "</div>";
						echo"<hr style='margin-top: 5px;margin-bottom: 5px;'>";

					}

					?>	
					</div>

				</div>
				
			</div>


			<?php
		}elseif ($do=='Add') { ?>
			
		<h1 class="text-center">Add New TODO</h1>
<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
						
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Title</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" name="name" class="form-control"  autocomplete ="off" required="required" placeholder="Title of the TODO">
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-4">
								
								<input type="text" name="description"  class=" password form-control"  placeholder="Describe the TODO">
								
							</div>
						</div>
						
						
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Due date</label>
							<div class="col-sm-10 col-md-4">
								<div style="margin-top:10px;">
									<input id="due" type="date" name="due" >
									
								</div>
								
							</div>
						</div>

						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Todo" class="btn btn-primary">
							</div>
						</div>
				</form>
			</div>
		<?php
		}
		elseif ($do=='Insert') {
			if ($_SERVER['REQUEST_METHOD']=='POST') {
				echo "<h1 class='text-center'>Insert Todo</h1>";
			echo "<div class ='container'>";
				//get the variable from the form

				
				$name 	  = $_POST['name'];
				$desc 	  = $_POST['description'];
				$due 	  =$_POST['due'];
				if (empty($_POST['due'])) {
					$due = date('Y-m-d');
				}
				
				$order = countCat('ID','todos')+1;
				/*
				$visible  = $_POST['visibility'];
				$comment  = $_POST['commenting'];
				$ads 	  = $_POST['ads'];*/

			
				
				

					//check if todo exist in DB
					
			    $check =checkItem1("Name","todos",$name,$userkey);
			    if ($check == 1) {
			    	$theMsg = "<div class='alert alert-danger'> Sorry this Todo Is Exist</div>";
			    	redirectHome($theMsg,'back');
			    }else{

					// insert users info in data base
					$stmt = $con ->prepare("INSERT INTO todos (Name, Description, Due, Ordering,UserID)
						VALUES(:zname, :zdesc,:zdue ,:zorder,:zuserid)" ); 
					$stmt->execute(array(
						'zname'		=> $name,
						'zdesc'		=> $desc,
						'zdue'		=> $due,
						'zorder'	=> $order,
						'zuserid'	=> $userkey 
						//'zvisible'	=> $visible,
						
						

						));


				// echo succes message

				 $theMsg="<div class='alert alert-success'>". $stmt->rowCount() . 'record inserted </div>';

				 	redirectHome($theMsg,'back');

				}    
			

			}else{
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">SORRY YOU CANT BROWSE THIS PAGE DIRECT</div>';
				redirectHome($theMsg,'back');
                echo "</div>";
			}
			echo "</div>";
			# code...
		}elseif ($do=='Edit') {

			// check if get request catID is numirec and get the integer value of it
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			//select all data depend this id
			$stmt = $con->prepare("SELECT * FROM todos WHERE  ID = ? ");
			//exexute query
			$stmt->execute(array($catid));
			//fetch the data
			$cat =$stmt->fetch();
			// the row count
			$count = $stmt->rowCount();
			// if there is such id show the form
			if ($count> 0) { ?>

			<h1 class="text-center">Edit TODO</h1>
<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid ?>" *
						>
						
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Title</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" name="name" class="form-control"  required="required" placeholder="Title of the TODO" value="<?php echo $cat['Name'] ?>">
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-4">
								
								<input type="text" name="description"  class=" password form-control"  placeholder="Describe the Todo" value="<?php echo $cat['Description'] ?>">
								
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Due</label>
							<div class="col-sm-10 col-md-4">
								
								<input type="date" name="due"  class=" password form-control"  value="<?php echo $cat['Due'] ?>">
								
							</div>
						</div>


						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" name="ordering" class="form-control" placeholder="Number to arrange the TODO" value="<?php echo $cat['Ordering'] ?>">
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Done</label>
							<div class="col-sm-10 col-md-4">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility']== 0) {
										echo "checked";
									}?> >
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility']== 1) {
										echo "checked";
									}?> >
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
					

						
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary">
							</div>
						</div>
				


		

		<?php 
				// else  there is no id show err message
				}else{
					echo "<div class='container'>";
					$theMsg='<div class ="alert alert-danger"> there is no such id</div>';
					redirectHome($theMsg);
					echo "</div>";
				}
			

		}elseif ($do=='Update') {
			echo "<h1 class='text-center'>Update Todo</h1>";
			echo "<div class ='container'>";
 
			if ($_SERVER['REQUEST_METHOD']=='POST') {
				//get the variable from the form
				$id = $_POST['catid'];
				$name = $_POST['name'];
				$desc = $_POST['description'];
				$due  =$_POST['due'];
				$order = $_POST['ordering'];
				$visible = $_POST['visibility'];
				

				
				// update the database with this is info

				$stmt =$con->prepare("UPDATE todos SET Name = ?,Description =?,Due = ? ,Ordering=? ,Visibility=?  WHERE ID = ?");
				 $stmt->execute(array($name,$desc,$due ,$order,$visible,$id));

				// echo succes message

				 $theMsg= "<div class='alert alert-success'>". $stmt->rowCount() . 'record updated</div>';
				 redirectHome($theMsg,'back');
				 
				

			}else{

				$theMsg= '<div class="alert alert-danger">SORRY YOU CANT BROWSE THIS PAGE DIRECT </div>';
				redirectHome($theMsg);
			}
			echo "</div>";

		}
		elseif ($do=='Delete') {
				echo "<h1 class='text-center'>DELETE TODO</h1>";
			echo "<div class ='container'>";
			
			// check if get request catid is numirec and get the integer value of it 
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			//selecct all data depend this id
			//$stmt = $con->prepare("SELECT * FROM users WHERE  UserID = ? LIMIT 1");
			$check=checkItem('ID','todos',$catid);
			
			/*exexute query
			$stmt->execute(array($userid));
			
			// the row count
			 $count = $stmt->rowCount();*/
			// if there is such id show the form
			if ($check > 0) { 

				$stmt = $con-> prepare("DELETE FROM todos WHERE ID = :zid");
				$stmt->bindParam(":zid",$catid);
				$stmt->execute();
				 $theMsg= "<div class='alert alert-success'>". $stmt->rowCount() . 'record deleted</div>';
				 redirectHome($theMsg,'back');

			}else{
				$theMsg= "<div class='alert alert-danger'>this id is not exist </div>";
				redirectHome($theMsg);
			}

			echo "</div>";
			}	
					elseif ($do=='Up') {
				
			echo "<div class ='container'>";
			
			// check if get request catid is numirec and get the integer value of it 
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;



			//selecct all data depend this id
			//$stmt = $con->prepare("SELECT * FROM users WHERE  UserID = ? LIMIT 1");
			$check=checkItem('ID','todos',$catid);
			$ordera=getOrdering($catid);
			$catb=getPrevCatId($ordera);
			$orderb=getOrdering($catb);

			/*exexute query
			$stmt->execute(array($userid));
			
			// the row count
			 $count = $stmt->rowCount();*/
			// if there is such id show the form
			if ($check > 0 && $ordera > 1) { 

				$stmt =$con->prepare("UPDATE todos SET Ordering=?   WHERE ID = ?");
				$stmt->execute(array($orderb,$catid));


				$stmt1 =$con->prepare("UPDATE todos SET Ordering=?  WHERE ID = ?");
				$stmt1->execute(array($ordera,$catb)); 

				 header('Location:todos.php');


			}else{
				//$theMsg= "<div class='alert alert-danger'>Its already in the 1st position </div>";
				redirectHome($theMsg);
			}

			echo "</div>";
			}	

			elseif ($do=='Down') {
				
			echo "<div class ='container'>";
			
			// check if get request catid is numirec and get the integer value of it 
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;



			//selecct all data depend this id
			//$stmt = $con->prepare("SELECT * FROM users WHERE  UserID = ? LIMIT 1");
			$check=checkItem('ID','todos',$catid);
			$ordera=getOrdering($catid);
			$catb=getNextCatId($ordera);
			$orderb=getOrdering($catb);

			/*exexute query
			$stmt->execute(array($userid));
			
			// the row count
			 $count = $stmt->rowCount();*/
			// if there is such id show the form
			if ($check > 0 ) { 

				$stmt =$con->prepare("UPDATE todos SET Ordering=?   WHERE ID = ?");
				$stmt->execute(array($orderb,$catid));


				$stmt1 =$con->prepare("UPDATE todos SET Ordering=?  WHERE ID = ?");
				$stmt1->execute(array($ordera,$catb)); 

				 header('Location:todos.php');


			}else{
				//$theMsg= "<div class='alert alert-danger'>Its already in the 1st position </div>";
				redirectHome($theMsg);
			}

			echo "</div>";
			}

			elseif ($do=='MakeDone') {
				
			echo "<div class ='container'>";
			
			// check if get request catid is numirec and get the integer value of it 
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			$check=checkItem('ID','todos',$catid);

			/*exexute query
			$stmt->execute(array($userid));
			
			// the row count
			 $count = $stmt->rowCount();*/
			// if there is such id show the form
			if ($check > 0 ) { 

				$stmt =$con->prepare("UPDATE todos SET Visibility=?   WHERE ID = ?");
				$stmt->execute(array(0,$catid));

				 header('Location:todos.php');


			}else{
				$theMsg= "<div class='alert alert-danger'>an error is accured </div>";
				redirectHome($theMsg);
			}

			echo "</div>";
			}


			elseif ($do=='MakeUnDone') {
				
			echo "<div class ='container'>";
			
			// check if get request catid is numirec and get the integer value of it 
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			$check=checkItem('ID','todos',$catid);

			/*exexute query
			$stmt->execute(array($userid));
			
			// the row count
			 $count = $stmt->rowCount();*/
			// if there is such id show the form
			if ($check > 0 ) { 

				$stmt =$con->prepare("UPDATE todos SET Visibility=?   WHERE ID = ?");
				$stmt->execute(array(1,$catid));

				 header('Location:todos.php');


			}else{
				$theMsg= "<div class='alert alert-danger'>an error is accured </div>";
				redirectHome($theMsg);
			}

			echo "</div>";
			}

		include $tpl . 'footer.php'; 

	}else{
		
		header('Location:index.php');
		exit();
	} 
	//ob_end_flush();

	?>