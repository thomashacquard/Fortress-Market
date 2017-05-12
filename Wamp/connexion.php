<?php ob_start();
session_start();
?>
<!DOCTYPE HTML>
<html>
<head> 
<link  rel="stylesheet" type="text/css" href="css/stylepage.css"> 
<link  rel="stylesheet" type="text/css" href="Footer/footer.css"> 
<meta charset="UTF-8">
</head>
<body>

<div class="textbox"> 
<form action="" method="post">
Identifiant: <input type="text" name="id" class="champ"><br>
Mot De Passe: <input type="password" name="mdp" class="champ"><br>
<input type="submit" name="submitbutton" id="valider">
</form>
</br>
<a href="inscription.php" style="font-size: 12px; margin-left: 40px;">Pas de compte? Inscrivez-vous ici!</a>
</div>

<?php
if(isset($_POST['submitbutton'])){ //check if form was submitted
$ndc =  $_POST['id'];
$mdp = $_POST['mdp'];
	if(empty($ndc) || empty($mdp)){
		echo '<script> alert("Veuillez remplir tous les champs."); </script>';
	}else{
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
}
include 'Footer/footer.php';?>
</body>
</html>