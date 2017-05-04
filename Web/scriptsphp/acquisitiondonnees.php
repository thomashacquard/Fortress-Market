<!DOCTYPE HTML>
<html>
<body>
<meta charset="UTF-8">
<?php
include("../mail/PHPMailerAutoload.php"); //import de la fonction d'utilisation des mails de la bibliothèque PHPMailer
$fileurl = "https://docs.google.com/uc?id=0BzRBjlEzHwv0UUxERjdDQVRzYk0&export=download";//téléchargement du fichier contenant la liste des objets
$itemlist = file_get_contents($fileurl);//lecture du fichier téléchargé
$jsonitemlist = json_decode($itemlist,true);//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
$itemamount = count($jsonitemlist);//on compte le nombre d'objets

for($n=0; $n<=$itemamount-1; $n++){//pour le nombre d'objets existants faire...

	$itemname = $jsonitemlist[$n];// attribution d'un nom d'objet à la variable itemname
	addDataToDatabase($itemname);//mise à jour de l'objet ayant le nom itemname
	
	$localdata = file_get_contents("../Data/Data.json");//lecture du fichier contenant les données des objets
	$arraylocaldata = json_decode($localdata, true);//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
	
	$itemLP = $arraylocaldata[$itemname]['Lowest_price'];//on va chercher le prix le plus bas dans la base de données pour l'objet demandé (ici: $itemname)
	
	$itemdata = file_get_contents("../Data/Objects/".$itemname.".json");//lecture du fichier contenant les données de l'objet $itemname pour voir qui suit le cour de cet objet
	$arrayitemdata = json_decode($itemdata, true);//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
	
	if(is_array($arrayitemdata) || !($arrayitemdata == null)){ //on vérifie que le fichier de l'objet n'est pas vide
		foreach($arrayitemdata as $key => $username){//s'il ne l'est pas on crée un boucle pour ayant pour variable le nom des utilisateurs qui suivent le cour de cet objet
			print_r($username);//debogage
			echo'</br>';//debogage
			echo'</br>';//debogage
				if(($username['valeur']-$itemLP>=0 && $username['type'] == "montant") || ($username['valeur']-$itemLP<=0 && $username['type'] == "descendant")){//on vérifie si les conditions nécessaire à l'envoi du mail sont réunies
					sendMailToUserMail($username['mail'], $username['valeur'], $itemname); //envoie du mail, voir la fonction sendMailToUserMail
					unset($arrayitemdata[$key]);//on retire cet utilisateur de la liste des personnes qui suivent le cour de cet objet
				}
			}
		}
	$newarrayitemdata = json_encode($arrayitemdata);//lorsque la traitement de l'objet est fini on encode la liste liée à cet objet en une liste de format JSON
	$itemdata = file_put_contents("../Data/Objects/".$itemname.".json", $newarrayitemdata);//on enregistre cette nouvelle liste
}


function CustomUrlEncode($string){//fonction utilisée pour transformer une chaîne de caractère pour pouvoir l'utiliser dans une URL
    $string = urlencode($string);//on utlise la fonction PHP pour encoder sous format URL une chaîne de caractères
    $string = str_replace('+','%20',$string);
    $string = str_replace('_','%5F',$string); //la fonction php n'est pas assez complète, on modifie alors les derniers caractèmes manuellement
    $string = str_replace('.','%2E',$string); 
    $string = str_replace('-','%2D',$string);
    return $string;//renvoie de la chaîne transformée
}
function getItemData($itemname){//fonction qui va chercher les données d'un objet grâce à son nom
    $encodeditemname = CustomUrlEncode($itemname);//utilisation de la fonction CustomUrlEncode crée précédemment
    $jsonitemdata = file_get_contents('http://steamcommunity.com/market/priceoverview/?appid=440&currency=3&market_hash_name='.$encodeditemname); //on va chercher les données de l'objet qui est en argument de la fonction, disponibles via l'API steam 
    $itemdata = json_decode($jsonitemdata,true);//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
    return $itemdata;//renvoi des données de l'objet
}
function getItemSuccess($itemdata){//fonction qui permet de déterminer si les données de l'objet que nous sommes allés chercher via l'API sont définies
	$success = $itemdata['success'];
	return $success;//renvoi du succès
}
function getItemVolume($itemdata){//fonction qui donne le nombre d'objets en vente grâce aux données de l'API que nous avons précédemment acquises
	if(getItemSuccess($itemdata) == true){//on vérifie que les données de l'objet sont définies
		if(isset($itemdata['volume'])){//on vérifie que la quantité d'objets n'est pas nul 
		$volume = $itemdata['volume'];
		}else{
			$volume = "NaN";//NaN: Not a Number
		}
	}
	return $volume;//renvoie de la quantité d'objets
}
function getItemLowestPrice($itemdata){//fonction qui donne le prix le plus bas d'un objet grâce aux données de l'API que nous avons précédemment acquises
	if(getItemSuccess($itemdata) == true){//on vérifie que les données de l'objet sont définies
		if(isset($itemdata['lowest_price'])){//on vérifie que le prix le plus bas n'est pas nul 
		$lowest_price = $itemdata['lowest_price'];
		}else{
			$lowest_price = "NaN";//NaN: Not a Number
		}
	}
	return $lowest_price;//renvoie du prix le plus bas
}
function getItemMedianPrice($itemdata){//fonction qui donne le prix médian d'un objet grâce aux données de l'API que nous avons précédemment acquises
	if(getItemSuccess($itemdata) == true){//on vérifie que les données de l'objet sont définies
		if(isset($itemdata['median_price'])){//on vérifie que le prix médian n'est pas nul 
		$median_price = $itemdata['median_price'];
		}else{
			$median_price = "NaN";//NaN: Not a Number
		}
	}
	return $median_price;//renvoie du prix médian
}
function addDataToDatabase($itemname){//fonction qui enregisqtre les données acquises via l'API dans le fichier de données des objets
	$localdata = file_get_contents("../Data/Data.json");//lecture du fichier contenant les données des objets
	$jsonlocaldata = json_decode($localdata, true);//le contenu du fichier étant sous format JSON, on le décode pour obtenir une liste (Array)
	$itemdata = getItemData($itemname);//utilisation de la fonction getItemData() définie précédemment
	$itemdata = str_replace(chr(0xE2).chr(0x82).chr(0xAC),'',$itemdata);//on retire le surplus de caractères liés à la conversion en euro par l'API
	echo "Mise à jour objet:".$itemname;//debogage
	print_r($itemdata);//debogage
	echo "</br>";//debogage
	echo "</br>";//debogage
	$itemarraydata = array("success"=>getItemSuccess($itemdata),"Volume"=>getItemVolume($itemdata),"Lowest_price"=>getItemLowestPrice($itemdata),"Median_price"=>getItemMedianPrice($itemdata));//on crée une nouvelle liste liée à l'objet
	$jsonlocaldata[$itemname] = $itemarraydata;//on met la nouvelle liste dans une liste de liste
	$newjsonlocaldata = json_encode($jsonlocaldata);//cette dernière est convertie en format JSON
	file_put_contents("../Data/Data.json",$newjsonlocaldata);//on enregiste la liste contenant toutes les informations sous le fichier Data.json
}
function sendMailToUserMail($mail, $seuil, $itemname){//fonction qui envoie un mail à un utilisateur
	
echo '<pre>';
$account="FortressMarket@gmail.com";//identifiants de connexion de l'adresse mail qui envoie les mail
$password="FMISN2017";
$from="FortressMarket@gmail.com";//équivalent à l'information "de:..." d'un mail
//$to=$mail; adresse réelle pour le debogage
$to="amatokus8669@gmail.com";//recepteur
$from_name="Notifications Fortress Market";//nom de l'envoyeur
//contenu du mail
$msg="<div>L'objet: <strong>".$itemname."</strong> que vous suiviez sur le site Fortress Market, a atteint le seuil de prix de <strong>".$seuil."€</strong> que vous avez au préalable établi."; // HTML message
$subject="Notification de prix d'objet";//objet du mail

//configurations liées à l'envoi de mail via la bibliothèque PHPMailer
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1;
$mail->SMTPAuth= true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // Or 587
$mail->isHTML(true);
$mail->Username= $account;
$mail->Password= $password;
$mail->SetFrom = $from;
$mail->FromName= $from_name;
$mail->Subject = $subject;
$mail->Body = $msg;
$mail->addAddress($to);
//fin des configurations liées à l'envoi de mail via la bibliothèque PHPMailer


if(!$mail->send()){//vérification de l'envoi du mail
 echo "Erreur lors de l'envoi du mail: " . $mail->ErrorInfo;
}else{
 echo "E-Mail envoyé";
}
echo '</pre>';
}
?>
</body>
</html>