<?php
require_once __DIR__."/config.php";
$resultN = "";
$resultC = "1";
$clientArray = array(array());
$channelArray = array();
$position = 0;

//this the only method you need to call from outside
function parseToHTML(){
 global $adminname , $admin_password;
 popen("sh ".__DIR__."/getClients.sh  $adminname $admin_password","r");
 popen("sh ".__DIR__."/getChannels.sh $adminname $admin_password","r");
	echo "<div style='color:white; width: 100%'>";	
	global $channelArray, $clientArray , $resultC , $position, $resultN, $path;
  	while($resultC !== false){
                $resultC = getClientInfo("cid=",$position);
                $resultN = getClientInfo("client_nickname=",$position);
		if(strpos($resultN,"serveradmin\sfrom\s[::1]") === false){
                if(!isset($clientArray[$resultC])) $clientArray[$resultC] = array();
		array_push($clientArray[$resultC],$resultN);
		}
        }
	$resultC = "1";	
	$position= 0;
	while($resultC !== false){
                $resultC = getChannelInfo("cid=",$position);
                $resultN = getChannelInfo("channel_name=",$position);
                $channelArray[$resultC] = $resultN;
		$spacer = explode("]",$resultN);
		if(strpos($resultN,"cspacer")){
		echo "<div id='$resultC'style='width:50%; margin: auto; text-align: center'><p>$spacer[1]</p>" .getClientsInChannel($resultC). "</div>";
		}
		elseif(strpos($resultN,"*spacer")){
		echo "<div id='$resultC'style='margin: auto;'><p>";
		for($i = 0; $i < 200; $i++){
		echo "$spacer[1]";
		}
		echo "</p>" .getClientsInChannel($resultC). "</div>";
		}elseif(strpos($resultN,"spacer")){
                echo "<div id='$resultC'style='width:50%; margin: auto; height: 19px'>". getClientsInChannel($resultC) . "</div>";
		}else{
		echo "<div id='$resultC' style='color:white; width: 100%'><p>$resultN</p>" . getClientsInChannel($resultC)  . "</div>";
		}
        }
	echo "</div>";

	}


function getClientsInChannel($channelid){
 global $clientArray;
 $html = "";
 if(isset($clientArray[$channelid])){
 foreach ($clientArray[$channelid] as $client){
  $client = preg_replace('/\\\\s/'," ",$client);
  $client = preg_replace('/\\\\p/',"|",$client);
  $client = preg_replace('/\\\\/',"",$client); 
  $html .= "<p style='font-size: 12px; margin-left: 1%'>".$client."</p>";
 }
 return $html;
}
}


function getChannelInfo($search,$start){
global $position;
$text = fread(fopen(__DIR__."/channels", 'r'),filesize(__DIR__."/channels"));
$posa = strpos($text, $search , $start);
if($posa === false){
return false;
}
$posa += strlen($search);
$posb = strpos($text, " ", $posa);
$len = $posb - $posa;
$position = $posb;
$test = substr($text , $posa , $len);
$test = preg_replace('/\\\\s/'," ",$test);
$test = preg_replace('/\\\\p/',"|",$test);
$test = preg_replace('/\\\\/',"",$test);
return preg_replace('/\\\\s/'," ",$test);
}

function getClientInfo($search,$start){
global $position;
$text = htmlspecialchars(fread(fopen("clients", 'r'),filesize("clients")));
$posa = strpos($text, $search , $start);
if($posa === false){
return false;
}
$posa += strlen($search);
$posb = strpos($text, " ", $posa);
$len = $posb - $posa;
$position = $posb;
return substr($text , $posa , $len);
}
