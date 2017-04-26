<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head>
	<link  rel="stylesheet" type="text/css" href="css/stylepage.css">
	<link  rel="stylesheet" type="text/css" href="css/modalstyle.css">
</head>
<body>
<?php include 'scriptsphp/verif.php';?>
	<div class="modalwrapper">
		<div class="modaltitle" id="modaltitleid">
			<p class="modaltitle" id="modaltitleid">Suivre l'objet: <?php echo $_POST['object']; ?></p>
		</div>
		<hr>
			</br>
			<p class="modalcontent" id="modalcontentid1">Adresse de notification: <?php echo $_SESSION['mail']; ?></p>
			<div id="container">
			</br>
				<p class="modalcontent" id="modalcontentid2">Seuil de la valeur du prix minimum :</p>
				<form class="modalform" id="modalformid" method="post">
					<input class="modalvalue modalcontent" id="modalvalueid" type="text" name="valeur">
					<p class="modalcontent" id="modalcontentid3">€</p>
					</br>
					</br>
					<p class="modalcontent" id="modalcontentid4">Pris le plus bas actuel: <?php echo $_POST['lp']; ?> €</p>
					</br>
					<hr>
			</div>
					<input name="object" type="hidden" value="<?php echo $_POST['object']; ?>">
					<input name="lp" type="hidden" value="<?php echo $_POST['lp']; ?>">
					<input class="modalsubmit modalcontent" id="modalsubmitid" name="submitbutton" type="submit" value="Valider &#8883">
					</br>
				</form>
	</div>
<?php
	if(isset($_POST['submitbutton'])){//validé?
		if(isset($_POST['valeur']) && $_POST['valeur'] != ''){//valeur entrée?
			$valeur = str_replace(",",".",$_POST['valeur']);//remplacement , en . pour les nombres a virgules
			if(is_numeric($valeur)){//on vérifie que tout ça est bien un nombre
				if($valeur != 0){
					$fichierUtilisateur = file_get_contents("Data/Users/".$_SESSION['utilisateur'].".json");
					$dataUtilisateur = json_decode($fichierUtilisateur, true);
					$dataUtilisateur[$_POST['object']] = $valeur;
					$dataUtilisateur = json_encode($dataUtilisateur);
					file_put_contents("Data/Users/".$_SESSION['utilisateur'].".json",$dataUtilisateur);
					
					$fichierObjet = file_get_contents("Data/Objects/".$_POST['object'].".json");
					$dataObjet = json_decode($fichierObjet, true);
					$dataObjet[$_SESSION['utilisateur']] = $_SESSION['mail'];
					$dataObjet = json_encode($dataObjet);
					file_put_contents("Data/Objects/".$_POST['object'].".json",$dataObjet);
					header("Location: /Fortress%20Market/objets.php");
				}else{echo '<script> alert("Veuillez entrer une valeur différente de 0.");</script>';}
			}
		}else{echo '<script>alert("Veuillez entrer une valeur.");</script>';}
	}
?>
</body>
</html>