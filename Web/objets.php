<?php session_start();
include 'Header/header.php';
include 'scriptsphp/verif.php'; ?>
<!DOCTYPE HTML>
<html>
<head> 
			<!-- Stylesheet -->
		<link  rel="stylesheet" type="text/css" href="css/stylepage.css">
		<link  rel="stylesheet" type="text/css" href="css/styletable.css">
		<link rel="stylesheet" type="text/css" href="Header/header.css">
		<link  rel="stylesheet" type="text/css" href="Footer/footer.css"> 
			<!-- Stylesheet -->
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body>
<div class="pagecontainer">

<script src="scriptsjs/ChargementTableau.js"></script>
<div id="Tableau">
<img src="Data/chargement.gif" alt="Chargement du tableau ..." width="10%" height="10%" style="margin-left:45%; margin-top:5%">
</div>

</div>


<?php include 'Footer/footer.php';?>
</body>
</html>