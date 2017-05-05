<?php session_start();


//obtention des données du fichier Data.json et décodage
$jsondatafile = file_get_contents("../Data/Data.json");
$jsondata = json_decode($jsondatafile);
//obtention des données du fichier Data.json et décodage

//définition de la fonction pour encoder le text pour qu'il soit utilisable dans une URL
function CustomUrlEncode($string) {
    $string = urlencode($string);
    $string = str_replace('+','%20',$string);
    $string = str_replace('_','%5F',$string); 
    $string = str_replace('.','%2E',$string); 
    $string = str_replace('-','%2D',$string);
    return $string;
}
//définition de la fonction pour encoder le text pour qu'il soit utilisable dans une URL


$userListFile = file_get_contents("../Data/Users/".$_SESSION['utilisateur'].".json");
$userListFileData = json_decode($userListFile, true);
    foreach($jsondata as $key => $item){
	if (array_key_exists($key,$userListFileData)){
		echo'<div class="conteneur">';
			echo'<div class="objet">';
				echo'<div id="nom">'.$key.'</div>';
				echo'<div id="informations">';
					echo'<img src="../Fortress%20Market/Data/Objects/Images/'.$key.'.png" id="image">';
					echo'<div class="data">';
						echo'<div id="volume" class="info"> Quantité: '.$item->Volume.'</div>';
						echo'<div id="lp" class="info">Pix le plus bas:'.$item->Lowest_price.' ('.str_replace(".",",",$userListFileData[$key]).')€</div>';
						echo'<div id="mp" class="info">Prix médian: '.$item->Median_price.'€</div>';
					echo'</div>';
				echo'</div>';
			echo'</div>';
		echo'<div>';
		echo'<form action="modal2.php" method="post">';
echo '<input type="hidden" name="lp" value="'.$item->Lowest_price.'">';
echo '<input type="hidden" name="object" value="'.$key.'">';
echo '<input type="hidden" name="userlp" value="'.$userListFileData[$key].'">';
		echo'<input type="image"
			src="/Fortress%20Market/Images/del.png"
			id="Button"
			name="del">';
		echo'</form>';
		echo'</div>';
		echo'</div>';
	}
}
?>
