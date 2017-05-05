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
	E-Mail: <input type="text" name="mail" class="champ"><br>
	Mot De Passe: <input type="password" name="mdp" class="champ"><br>
	Vérification du Mot De Passe: <input type="password" name="mdpverif" class="champ"><br>
	<input type="submit" name="submitbutton" id="valider">
</form>
</br>
<a href="connexion.php" style="font-size: 12px; margin-left: 40px;">Déjà inscrit? connectez-vous ici!</a>
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
				$comptes = file_get_contents("Data/comptes.json"); 
			if(isset(json_decode($comptes)->$id)){
				echo '<script> alert("Un compte lié à cet identifiant existe déjà!"); </script>';
			}else{
				$valcomptes = json_decode($comptes,true);
				$nouveaucompte = array("mdp"=>$mdp,"mail"=>$mail);
				$valcomptes[$id] = $nouveaucompte;
				print_r($valcomptes);
				$nouvcomptes = json_encode($valcomptes);
				file_put_contents("Data/comptes.json",$nouvcomptes);
				fopen("Data/Users/".$id.".json", "w");
				fclose();
				header("Location: /Fortress%20Market/confirmation.php");
				exit();
			}
		}
	}
}
include 'Footer/footer.php';?>
</body>
</html>
