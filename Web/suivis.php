<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head> 
			<!-- Stylesheet -->
		<link  rel="stylesheet" type="text/css" href="css/stylepage.css">
		<link  rel="stylesheet" type="text/css" href="css/styletable.css">
		<link rel="stylesheet" type="text/css" href="Header/header.css">
			<!-- Stylesheet -->
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body>
<div class="pagecontainer">
<?php include 'Header/header.php';?>
<?php include 'scriptsphp/verif.php';?>

<script src="scriptsjs/ChargementTableauUser.js"></script>
<div id="Tableau">
<img src="Data/chargement.gif" alt="Chargement du tableau ..." width="10%" height="10%" style="margin-left:45%; margin-top:5%">
</div>

</div>


</body>
</html>