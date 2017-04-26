<?php session_start();?>

<!DOCTYPE HTML>
<html>
<head> 
<link  rel="stylesheet" type="text/css" href="css/stylepage.css"> 
</head>
<body>

<div class="textbox"> 
<form action="" method="post">
Identifiant: <input type="text" name="id" class="champ"><br>
Mot De Passe: <input type="password" name="mdp" class="champ"><br>
<input type="submit" name="submitbutton" id="valider">
</form>
</div>

<?php
if(isset($_POST['submitbutton'])){ //check if form was submitted
$ndc =  $_POST['id'];
$mdp = $_POST['mdp'];

$comptes = file_get_contents("Data/comptes.json");
$valcomptes = json_decode($comptes);

if(isset($valcomptes->$ndc) || !file_exists("/Fortress%20Market/Data/Users/".$ndc.".json")){
	if($mdp == $valcomptes->$ndc->mdp){
		$_SESSION['utilisateur'] = $ndc;
		$_SESSION['mail'] = $valcomptes->$ndc->mail;
		$_SESSION['mdp'] = $mdp;
		header("Location: /Fortress%20Market/accueil.php");
	}else{
	echo '<script> alert("Mot de passe invalide"); </script>';
	}
}else{
	echo '<script> alert("Nom de compte invalide"); </script>';
}
}
?>
</body>
</html>