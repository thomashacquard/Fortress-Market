<?php
$fileurl = "https://docs.google.com/uc?id=0BzRBjlEzHwv0UUxERjdDQVRzYk0&export=download";
$itemlist = file_get_contents($fileurl);
$jsonitemlist = json_decode($itemlist,true);
$itemamount = count($jsonitemlist);
for($n=0; $n<=$itemamount-1; $n++){
$itemname = $jsonitemlist[$n];
addDataToDatabase($itemname);
}


function CustomUrlEncode($string) {
    $string = urlencode($string);
    $string = str_replace('+','%20',$string);
    $string = str_replace('_','%5F',$string); 
    $string = str_replace('.','%2E',$string); 
    $string = str_replace('-','%2D',$string);
    return $string;
}

function getItemData($itemname){
    $encodeditemname = CustomUrlEncode($itemname);
    $itemdata = file_get_contents('http://steamcommunity.com/market/priceoverview/?appid=440&currency=3&market_hash_name='.$encodeditemname);
    $jsonitemdata = json_decode($itemdata,true);
    return $jsonitemdata;
}

function getItemSuccess($itemdata){
	$success = $itemdata['success'];
	return $success;
}
function getItemVolume($itemdata){
	if(getItemSuccess($itemdata) == true){
		if(isset($itemdata['volume'])){
		$volume = $itemdata['volume'];
		}else{
			$volume = "NaN";
		}
	}
	return $volume;
}
function getItemLowestPrice($itemdata){
	if(getItemSuccess($itemdata) == true){
		if(isset($itemdata['lowest_price'])){
		$lowest_price = $itemdata['lowest_price'];
		}else{
			$lowest_price = "NaN";
		}
	}
	return $lowest_price;
}
function getItemMedianPrice($itemdata){
	if(getItemSuccess($itemdata) == true){
		if(isset($itemdata['median_price'])){
		$median_price = $itemdata['median_price'];
		}else{
			$median_price = "NaN";
		}
	}
	return $median_price;
}


function addDataToDatabase($itemname){
	$localdata = file_get_contents('C:\\Users\\Paul\\Google Drive\\Steam Market Data\\Data.json');
	$jsonlocaldata = json_decode($localdata, true);
	$itemdata = getItemData($itemname);
	$itemdata = str_replace(chr(0xE2).chr(0x82).chr(0xAC),'',$itemdata);
	print_r($itemdata);
	$itemarraydata = array("success"=>getItemSuccess($itemdata),"Volume"=>getItemVolume($itemdata),"Lowest_price"=>getItemLowestPrice($itemdata),"Median_price"=>getItemMedianPrice($itemdata));
	$jsonlocaldata[$itemname] = $itemarraydata;
	$newjsonlocaldata = json_encode($jsonlocaldata);
	fopen("Data/Objects/".$itemname.".json", "w");
echo '<pre>'; 
	print_r($itemdata);
echo '</pre>'; 
	file_put_contents("C:\\Users\\Paul\\Google Drive\\Steam Market Data\\Data.json",$newjsonlocaldata);
}

?>