<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head> 
			<!-- Stylesheet -->
		<link  rel="stylesheet" type="text/css" href="css/stylepage.css">
		<link rel="stylesheet" type="text/css" href="Header/header.css">
			<!-- Stylesheet -->
</head>
<body>
<div class="pagecontainer">
<?php include 'Header/header.php';?>
<?php include 'scriptsphp/verif.php';?>
<h1>Bienvenue <?php echo $utilisateur;?> !</h1>
<a href="deconnexion.php">Se déconnecter</a>

</div>

</body>
</html>