<!DOCTYPE HTML>
<html>
<body>
<meta charset="UTF-8">

<?php

//import de la fonction d'utilisation des mails de la bibliothèque PHPMailer
include("../mail/PHPMailerAutoload.php"); 

//lecture du fichier de données
$itemlist = file_get_contents("../Data/ItemList.json");

//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
$jsonitemlist = json_decode($itemlist,true);

//debogage-------------->
echo '<pre>';
print_r($jsonitemlist);
echo '</pre>';
//<--------------debogage

//on compte le nombre d'objets
$itemamount = count($jsonitemlist);

//BOUCLE PRINCIPALE ------------------------------------------->
//pour le nombre d'objets existants faire...
for($n=0; $n<=$itemamount-1; $n++){

	// attribution d'un nom d'objet à la variable itemname
	$itemname = $jsonitemlist[$n];
	
	//mise à jour de l'objet ayant le nom itemname
	addDataToDatabase($itemname);
	
	//lecture du fichier contenant les données des objets
	$localdata = file_get_contents("../Data/Data.json");
	
	//on le decode
	$arraylocaldata = json_decode($localdata, true);
	
	//on va chercher le prix le plus bas dans la base de données pour l'objet demandé
	//(ici: $itemname)
	$itemLP = $arraylocaldata[$itemname]['Lowest_price'];
	$itemLP =str_replace(",","." ,$itemLP);
	//lecture du fichier contenant les données de l'objet $itemname
	//pour voir qui suit le cour de cet objet
	$itemdata = file_get_contents("../Data/Objects/".$itemname.".json");
	
	//on le decode
	$arrayitemdata = json_decode($itemdata, true);
	
	//on vérifie que le fichier de l'objet n'est pas vide
	if(is_array($arrayitemdata) || !($arrayitemdata == null)){
		
		//s'il ne l'est pas on crée un boucle pour ayant pour variable le nom des
		//utilisateurs qui suivent le cour de cet objet
		foreach($arrayitemdata as $unsername => $userdata){
			
			//debogage-------------->
			echo'<pre><strong>    Utilisateurs qui suivent cet objet: </strong>';
			echo'</br>        - '.$unsername.' ('.$userdata['valeur'].'€)';
			echo'</pre>';
			//<--------------debogage
			
				//on vérifie si les conditions nécessaire à l'envoi du mail sont réunies
				if(($userdata['valeur']-$itemLP<=0 && $userdata['type'] == "montant")
					|| ($userdata['valeur']-$itemLP>=0 && $userdata['type'] == "descendant")){
					//envoie du mail, voir la fonction sendMailToUserMail
					sendMailToUserMail($userdata['mail'], $userdata['valeur'], $itemname); 
					
					//on retire cet utilisateur de la liste des personnes qui suivent le 
					//cour de cet objet
					unset($arrayitemdata[$unsername]);
					
					$fichierutilisateur = file_get_contents("../Data/Users/".$unsername.".json");
					$datautilisateur = json_decode($fichierutilisateur, true);
					unset($datautilisateur[$itemname]);
					$newdatautilisateur = json_encode($datautilisateur);
					file_put_contents("../Data/Users/".$unsername.".json", $newdatautilisateur);
				}
			}
		}
		if($arrayitemdata == null || $arrayitemdata == ""){
			
			$arrayitemdata = array();
			$newarrayitemdata = json_encode($arrayitemdata);
			
		}else{
			
			//lorsque la traitement de l'objet est fini
			//on encode la liste liée à cet objet en une liste de format JSON
			$newarrayitemdata = json_encode($arrayitemdata);
		
		}
	//on enregistre cette nouvelle liste
	$itemdata = file_put_contents("../Data/Objects/".$itemname.".json", $newarrayitemdata);
	

	}
//<------------------------------------------- BOUCLE PRINCIPALE

function CustomUrlEncode($string){

    //on utlise la fonction PHP pour encoder sous format URL une chaîne de caractères
	$string = urlencode($string);
	
	
    $string = str_replace('+','%20',$string);//la fonction php urlencode()
    $string = str_replace('_','%5F',$string);//n'est pas assez complète
    $string = str_replace('.','%2E',$string);//on modifie alors
    $string = str_replace('-','%2D',$string);//les derniers caractèmes manuellement
	
	//renvoie de la chaîne transformée
    return $string;
}

//FONCTIONS: Acquisition des données relatives à un objet ------------------------>
function getItemData($itemname){
	
	//utilisation de la fonction CustomUrlEncode crée précédemment
    $encodeditemname = CustomUrlEncode($itemname);
	
	//on va chercher les données de l'objet qui est en argument de la fonction,
	//disponibles via l'API steam 
	$jsonitemdata = file_get_contents(
	'http://steamcommunity.com/market/priceoverview/
	?appid=440&currency=3&market_hash_name='.$encodeditemname); 
	
	//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
	$itemdata = json_decode($jsonitemdata,true);
	
	//renvoi des données de l'objet
    return $itemdata;
}

function getItemSuccess($itemdata){
	
	$success = $itemdata['success'];
	
	//renvoi du succès
	return $success;
}
function getItemVolume($itemdata){
	
	//on vérifie que les données de l'objet sont définies
	if(getItemSuccess($itemdata) == true){
		
		//on vérifie que la quantité d'objets n'est pas nul 
		if(isset($itemdata['volume'])){
			
			$volume = $itemdata['volume'];
			
		}else{
			
			$volume = "NaN";//NaN: Not a Number
			
		}
	}
	
	//renvoi de la quantité d'objets
	return $volume;
	
}

function getItemLowestPrice($itemdata){
	
	//on vérifie que les données de l'objet sont définies
	if(getItemSuccess($itemdata) == true){
	
		//on vérifie que le prix le plus bas n'est pas nul
		if(isset($itemdata['lowest_price'])){ 
		
			$lowest_price = $itemdata['lowest_price'];
			
		}else{
			
			$lowest_price = "NaN";//NaN: Not a Number
			
		}
	}
	
	//renvoi du prix le plus bas
	return $lowest_price;
	
}

function getItemMedianPrice($itemdata){
	
	//on vérifie que les données de l'objet sont définies
	if(getItemSuccess($itemdata) == true){
		
		//on vérifie que le prix médian n'est pas nul 
		if(isset($itemdata['median_price'])){
			
			$median_price = $itemdata['median_price'];
			
		}else{
			
			$median_price = "NaN";//NaN: Not a Number
		
		}
	}
	
	//renvoi du prix médian
	return $median_price;
}
//<------------------------ FONCTIONS: Acquisition des données relatives à un objet



//FONCTION: Enregistrement des données acquises ------------------------>

//fonction qui enregisqtre les données acquises via l'API dans le fichier de données des objets
function addDataToDatabase($itemname){

	//lecture du fichier contenant les données des objets
	$localdata = file_get_contents("../Data/Data.json");
	
	//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
	$jsonlocaldata = json_decode($localdata, true);
	
	//utilisation de la fonction getItemData() définie précédemment
	$itemdata = getItemData($itemname);
	
	//on retire le surplus de caractères liés à la conversion en euro par l'API
	$itemdata = str_replace(chr(0xE2).chr(0x82).chr(0xAC),'',$itemdata);
	
	
	//debogage --------------------------------------------->
	echo "<strong>".$itemname." </strong>";
	print_r($itemdata);
	echo "<pre>";
	echo "</pre>";
	//<--------------------------------------------- debogage
	
	$itemarraydata = array("success"=>getItemSuccess($itemdata),
							"Volume"=>getItemVolume($itemdata),
							"Lowest_price"=>getItemLowestPrice($itemdata),
							"Median_price"=>getItemMedianPrice($itemdata));
	
	//on met la nouvelle liste dans une liste de liste
	$jsonlocaldata[$itemname] = $itemarraydata;
	
	//cette dernière est convertie en format JSON
	$newjsonlocaldata = json_encode($jsonlocaldata);
	
	//on enregiste la liste contenant toutes les informations sous le fichier Data.json
	file_put_contents("../Data/Data.json",$newjsonlocaldata);
}

//<------------------------ FONCTION: Enregistrement des données acquises



//FONCTION: Envoi de mails ------------------------>

function sendMailToUserMail($adresse, $seuil, $itemname){
	
	echo '<pre>';
	
	//identifiants de connexion de l'adresse mail qui envoie les mail ----------->
	$account="FortressMarket@gmail.com";
	$password="FMISN2017";
	//<----------- identifiants de connexion de l'adresse mail qui envoie les mail
	
	//équivalent à l'information "de:..." d'un mail
	$from="FortressMarket@gmail.com";
	
	//$to=$mail; adresse réelle pour le debogage
	$to="testfortressmarket@gmail.com";//recepteur
	
	//nom de l'envoyeur
	$from_name="Notifications Fortress Market";
	
	//objet du mail
	$subject="Notification de prix d'objet";
	
	//contenu du mail en HTML
	$msg="<div>L'objet: <strong>".$itemname."</strong> que vous suiviez sur le site
	Fortress Market, a atteint le seuil de prix de <strong>".$seuil."€</strong>
	que vous avez au préalable établi."; 
	
	
	//configurations liées à l'envoi de mail via la bibliothèque PHPMailer ---------->
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 1;
	$mail->SMTPAuth= true;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // Ou 587
	$mail->isHTML(true);
	$mail->Username= $account;
	$mail->Password= $password;
	$mail->SetFrom = $from;
	$mail->FromName= $from_name;
	$mail->Subject = $subject;
	$mail->Body = $msg;
	$mail->addAddress($to);
	//<---------- configurations liées à l'envoi de mail via la bibliothèque PHPMailer
	
	
	//vérification de l'envoi du mail
	if(!$mail->send()){
		echo "Erreur lors de l'envoi du mail: " . $mail->ErrorInfo;
	}else{
		echo "E-Mail envoyé à l'adresse:".$adresse;
	}
	
	echo '</pre>';
}
//<------------------------ FONCTION: Envoi de mails

//header("Refresh:0");

?>



</body>
</html>