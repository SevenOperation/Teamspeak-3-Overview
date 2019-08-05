<?php
require_once __DIR__."/config.php";
$resultN = "";
$resultC = "1";
$clientArray = array(array());
$channelArray = array();
$position = 0;


//This method returns a small javascript which repeates the character in a *spacer, onload of the window, until the end of the screen width is reached
function getJavaScript(){
return "\r\n window.onload = function() {
repeat_char();
} 
function repeat_char(){ 
var to_repeat = document.getElementsByClassName('repeated');
var finalstring = '';
for(var x = 0; x < to_repeat.length; x++) {
var size = parseFloat(window.getComputedStyle(to_repeat[x],null).getPropertyValue('font-size')) * 2; 
var repeat = parseInt(document.getElementById('teamspeak-overview').offsetWidth / size);
finalstring = '';
var char = to_repeat[x].innerHTML;
for (var y = 0; y < repeat; y++) { 
finalstring += char; } 
to_repeat[x].innerHTML = finalstring }
}";
}


//this an the method above are the only ones you need to call from outside
function parseToHTML(){
 global $adminname , $admin_password;
 popen("sh ".__DIR__."/getClients.sh  $adminname $admin_password","r");
 popen("sh ".__DIR__."/getChannels.sh $adminname $admin_password","r");
	echo "<div id='teamspeak-overview' style='color:white; width: 100%'>";	
	global $channelArray, $clientArray , $resultC , $position, $resultN, $path;
  	while($resultC !== false){
                $resultC = getClientInfo("cid=",$position);
                $resultN = getClientInfo("client_nickname=",$position);
		if(strpos($resultN,"serveradmin") === false){
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
		echo "<div id='$resultC'style='margin: auto;'><p class='repeated' style='font-family:Times New Roman'>";
		echo "$spacer[1]";
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
