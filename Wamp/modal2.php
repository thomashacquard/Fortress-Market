<?php session_start();
include 'scriptsphp/verif.php';?>
<!DOCTYPE HTML>
<html>
<head>
	<link  rel="stylesheet" type="text/css" href="css/stylepage.css">
	<link  rel="stylesheet" type="text/css" href="css/modalstyle.css">
		<link  rel="stylesheet" type="text/css" href="Footer/footer.css"> 
		<meta charset="UTF-8"> 
</head>
<body>

	<div class="modalwrapper">
		<div class="modaltitle" id="modaltitleid">
			<p class="modaltitle" id="modaltitleid">Ne plus suivre l'objet: <?php echo $_POST['object']; ?></p>
		</div>
		<hr>
			</br>
			<p class="modalcontent" id="modalcontentid1">Adresse de notification utilisée: <?php echo $_SESSION['mail']; ?></p>
			<div id="container">
			</br>
				<p class="modalcontent" id="modalcontentid2">Seuil de la valeur du prix minimum choisi : <?php echo $_POST['userlp']; ?>&euro;</p>
				<form class="modalform" id="modalformid" method="post">
					</br>
					</br>
					<hr>
			</div>
					<input name="object" type="hidden" value="<?php echo $_POST['object']; ?>">
					<input name="userlp" type="hidden" value="<?php echo $_POST['userlp']; ?>">
					<input class="modalsubmit modalcontent" id="modalsubmitid" name="submitbutton" type="submit" value="Valider &#8883">
					</br>
				</form>
	</div>
<?php
	if(isset($_POST['submitbutton'])){//validé?
		$fichierUtilisateur = file_get_contents("Data/Users/".$_SESSION['utilisateur'].".json");
		$dataUtilisateur = json_decode($fichierUtilisateur, true);
		unset($dataUtilisateur[$_POST['object']]);
		$dataUtilisateur = json_encode($dataUtilisateur);
		file_put_contents("Data/Users/".$_SESSION['utilisateur'].".json",$dataUtilisateur);
		
		$fichierObjet = file_get_contents("Data/Objects/".$_POST['object'].".json");
		$dataObjet = json_decode($fichierObjet, true);
		unset($dataObjet[$_SESSION['utilisateur']]);
		$dataObjet = json_encode($dataObjet);
		file_put_contents("Data/Objects/".$_POST['object'].".json",$dataObjet);
		header("Location: /Fortress%20Market/suivis.php");
	}
include 'Footer/footer.php';?>
</body>
</html>