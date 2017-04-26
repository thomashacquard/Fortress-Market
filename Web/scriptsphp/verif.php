<?php 
	if(isset($_SESSION['utilisateur'])){
	$utilisateur = $_SESSION['utilisateur'];
		}else{
	header("Location: /Fortress%20Market/alerte.php");
		}
?>