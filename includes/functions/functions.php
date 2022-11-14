<?php

/*
**title func v1.0 that echo in case has the variable $pagetitle and echo default title for others*/

function getTitle() {

	GLOBAL $pageTitle;

	if (isset($pageTitle)) {
		echo $pageTitle;
	}else {
		echo "default";
	}

} 

/* redirect function  v2.0*/

function redirectHome($theMsg,$url=null,$second = 3){

	if ($url === null) {
		$url ='index.php';
		
	}
	else{
		$url = isset($_SERVER['HTTP_REFERER'])&& $_SERVER['HTTP_REFERER']!== '' ? $_SERVER['HTTP_REFERER']:'index.php';
		
		
	}
	echo $theMsg;
	echo "<div class ='alert alert-info'>you will be redirected to $url after. $second. </div>";
	header("refresh:$second;url=$url");
	exit();

}
/*
**function to check items in database v1.0*/

function checkItem($select,$from,$value){
	GLOBAL $con;
	$statement2 = $con->prepare("SELECT $select FROM $from WHERE $select = ?"); 
	$statement2->execute(array($value));
	$count =$statement2->rowCount();
	return $count;
}
function checkItem1($select,$from,$value,$value2){
	GLOBAL $con;
	$statement2 = $con->prepare("SELECT $select FROM $from WHERE $select = ? and UserID = ?"); 
	$statement2->execute(array($value,$value2));
	$count =$statement2->rowCount();
	return $count;
}

/** Coun tnumber of Items function v 1.0
**function to count nnumber of items rows*/

function countItems($item,$table){

	GLOBAL $con;
	$stmt2= $con->prepare("SELECT COUNT($item) from $table");
		$stmt2-> execute();
		return $stmt2->fetchColumn();

}

function countCat($cat,$table){

	GLOBAL $con;
	$stmt2= $con->prepare("SELECT COUNT($cat) from $table");
		$stmt2-> execute();
		return $stmt2->fetchColumn();

}


function countCatDone($cat,$table,$value){

	GLOBAL $con;
	$stmt2= $con->prepare("SELECT COUNT($cat) from $table WHERE UserID = $value AND Visibility = 0 ");
		$stmt2-> execute();
		return $stmt2->fetchColumn();

}

function countCatUnDone($cat,$table,$value){

	GLOBAL $con;
	$stmt2= $con->prepare("SELECT COUNT($cat) from $table WHERE UserID = $value AND Visibility = 1");
		$stmt2-> execute();
		return $stmt2->fetchColumn();

}


function getOrdering($value){

	GLOBAL $con;
	$statement2 = $con->prepare("SELECT Ordering FROM todos WHERE ID = ?"); 
	$statement2->execute(array($value));
	$row =$statement2->fetch();
	$count =$statement2->rowCount();
	return $row['Ordering'];

}

function getNextCatId($value){

	GLOBAL $con;
	$statement2 = $con->prepare("SELECT ID FROM todos WHERE Ordering > ? ORDER BY Ordering ASC LIMIT 1"); 
	$statement2->execute(array($value));
	$row =$statement2->fetch();
	$count =$statement2->rowCount();
	return $row['ID'];

}
function getPrevCatId($value){

	GLOBAL $con;
	$statement2 = $con->prepare("SELECT ID FROM todos WHERE Ordering < ? ORDER BY Ordering DESC LIMIT 1"); 
	$statement2->execute(array($value));
	$row =$statement2->fetch();
	$count =$statement2->rowCount();
	return $row['ID'];

}








/** Get latest record functions v 1.0
**function to get lates items from data base*/

function getLatest($select,$table,$order,$limit){

	GLOBAL$con;
	$getStmt =$con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");
	$getStmt->execute();
	$rows =$getStmt->fetchAll();
	return $rows;
}
