<?php session_start();?>

<!DOCTYPE HTML>
<html>
<head> 
<link  rel="stylesheet" type="text/css" href="css/stylepage.css"> 
</head>
<body>

<div class="textbox"> #Balise générique de type block

<form action="" method="post">	#La balise form est utilisée pour créer un formulaire
	Identifiant: <input type="text" name="id" class="champ"><br>
	E-Mail: <input type="text" name="mail" class="champ"><br>
	Mot De Passe: <input type="password" name="mdp" class="champ"><br>
	Vérification du Mot De Passe: <input type="password" name="mdpverif" class="champ"><br>
	<input type="submit" name="submitbutton" id="valider">	#c'est le bouton
</form>
</div>

<?php
if(isset($_POST['submitbutton'])){//check if form was submitted	#la variable doit être définie et est différente de 0
	$id = $_POST['id'];	 #demander a paul pour les 4 lignes
	$mail = $_POST['mail'];
	$mdp = $_POST['mdp'];
	$mdpverif = $_POST['mdpverif'];
	if(empty($id) || empty($mail) || empty($mdp) || empty($mdpverif)){	#les cases ne doivent pas être vides
		echo '<script> alert("Veuillez remplir tous les champs."); </script>';
	}else{
		if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){ #on filtre l'adresse mail saisie pour s'assurer qu'elle soit valide
			echo '<script> alert("Email invalide!"); </script>';
		}elseif($_POST['mdp'] != $_POST['mdpverif']) {	#si la valeur du mdp est différente que sa valeur rentrée précédemment
			echo '<script> alert("Les mots de passe ne sont pas similaires"); </script>';
		}else{
		
			if(isset(json_decode($comptes)->$ndc)){ #on verifie que le compte n'existe pas déjà
				echo '<script> alert("Un compte lié à cet identifiant existe déjà!"); </script>';
			}else{
				$ndc =  $_POST['id']; #je comprends pas l'interet des dernieres lignes
				$mdp = $_POST['mdp'];
				$mail = $_POST['mail'];;
				$comptes = file_get_contents("Data/comptes.json");
				$valcomptes = json_decode($comptes,true);
				$nouveaucompte = array("mdp"=>$mdp,"mail"=>$mail);
				$valcomptes[$ndc] = $nouveaucompte;
				print_r($valcomptes);
				$nouvcomptes = json_encode($valcomptes);
				file_put_contents("Data/comptes.json",$nouvcomptes);
				fopen("Data/Users/".$ndc.".json", "w");
				fclose();
				header("Location: /Fortress%20Market/confirmation.php");
				exit();
			}
		}
	}
}
?>
</body>
</html>
