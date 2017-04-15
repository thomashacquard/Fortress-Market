<?php session_start();?>

<!DOCTYPE HTML>
<html>
<head> 
<link  rel="stylesheet" type="text/css" href="stylepage.css"> 
</head>
<body>

<div class="textbox"> 
<form action="" method="post">
Identifiant: <input type="text" name="id" class="champ"><br>
E-Mail: <input type="text" name="mail" class="champ"><br>
Mot De Passe: <input type="password" name="mdp" class="champ"><br>
Vérification du Mot De Passe: <input type="password" name="mdpverif" class="champ"><br>
<input type="submit" name="submitbutton" id="valider">
</form>
</div>

<?php
if(isset($_POST['submitbutton'])){//check if form was submitted
	$id = $_POST['id'];
	$mail = $_POST['mail'];
	$mdp = $_POST['mdp'];
	$mdpverif = $_POST['mdpverif'];
	if(empty($id) || empty($mail) || empty($mdp) || empty($mdpverif)){
		echo '<script> alert("Veuillez remplir tous les champs."); </script>';
	}else{
		if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
			echo '<script> alert("Email invalide!"); </script>';
		}elseif($_POST['mdp'] != $_POST['mdpverif']) {
			echo '<script> alert("Les mots de passe ne sont pas similaires"); </script>';
		}else{
		
			if(isset(json_decode($comptes)->$ndc)){
				echo '<script> alert("Un compte lié à cet identifiant existe déjà!"); </script>';
			}else{
				$ndc =  $_POST['id'];
				$mdp = $_POST['mdp'];
				$mail = $_POST['mail'];;
				$comptes = file_get_contents("comptes.json");
				$valcomptes = json_decode($comptes,true);
				$nouveaucompte = array("mdp"=>$mdp,"mail"=>$mail);
				$valcomptes[$ndc] = $nouveaucompte;
				print_r($valcomptes);
				$nouvcomptes = json_encode($valcomptes);
				file_put_contents("comptes.json",$nouvcomptes);
				fopen("Data/Users/".$ndc.".json", "w");
				fclose();
				header("Location: /Projet%20ISN/Fortress%20Market/confirmation.php");
				exit();
			}
		}
	}
}
?>
</body>
</html>
