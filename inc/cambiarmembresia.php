<?php
error_reporting(0);
session_start();

if($_SESSION['rtSuperAdmin']==1){

	if ($_SESSION['rtMembresia']==1)
		$_SESSION['rtMembresia']=3;
	else
		$_SESSION['rtMembresia']=1;

}

//echo $_SESSION['rtSuperAdmin']."/";
//echo $_SESSION['rtMembresia'];

header("Location: /");
exit();

?>