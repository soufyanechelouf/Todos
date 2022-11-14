<?php



$do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 



//if th page is main page

if($do == 'Manage'){
	echo "welcoome you are in manage TODO page";
	echo '<a href="page.php.do=add">Add new TODO +</a>';

}elseif ($do == 'Add') {
	echo 'you are in add TODO page';

}elseif ($do == 'Insert') {
	echo 'you are in insert TODO page';

}else  {
	echo 'error there is no page with this name';
}
