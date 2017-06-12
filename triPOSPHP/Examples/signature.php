<?php
$sigdata="/////50B9QGdAfMBnQHxAZ0B7wGcAe0BnAHsAZwB6wGbAesBmgHqAZoB6wGZAewBmAHuAZcB8gGWAfgBlAH/AZMBAwKQAQ0CjgEZAosBJQKJASwChwE5AoUBRgKEAUwCggFZAoEBZAKBAW8CgQF5AoABfgKAAYcCgAGQAoABlQKAAaMCgAGsAoABsQJ/AbwCfgHGAn4BywJ9AdUCfAHeAnsB5gJ6Ae4CegHyAnkB+gJ4AQADeAEEA3cBCQN2AQ4DdgETA3UBFwN0ARoDdAEcA3QBHgNzASADcgEdA3IBGQNyARMDcgEPA3MBBQN0AfoCdAH0Av////96AYMCegGEAnkBhAJ4AYUCdwGGAncBhwJ1AYkCdAGLAnQBjQJ0AZACdQGSAncBlQJ7AZcCgQGYAoQBmAKMAZgClgGWApwBlQKoAZICtAGNAroBiwLHAYYC0wGCAt4BfgLpAXwC7QF7AvYBeQL+AXkCBQJ5AgoCeQIPAngCEgJ4AhYCdwIaAnYCHAJ1Ah8CdAIiAnICJAJxAiYCcAInAm8CKAJvAikCbgIpAm0CKQJsAikCawIpAmoC/////ywCHwItAh4CLQIdAi4CHAIvAhsCMAIZAjECGAIyAhYCMwIVAjQCFAI1AhYCNQIXAjYCHAI3AiQCNwIpAjkCNAI6AkMCOwJLAjwCUwI+AmcCPwJ7AkAChgJCApwCQwKyAkMCvQJEAtICRALdAkQC5gJFAvgCRQIIA0UCDwNFAhoDRQIhA0QCJgNEAigDRAIqA0MCLANCAi0DQgIuA0ECLwNCAi8DQgIuA0ICLQNDAioD/////8wCNwLMAjYCzAI1AswCNALMAjMCzAIyAssCMgLLAjECywIzAssCOALLAkACygJGAsoCTALJAlsCxwJsAsYCfwLEApMCwwKcAsECrwLBArgCwQLIAsECzgLCAtoCxQLjAsYC5gLKAuwCzALuAtAC8wLSAvQC1wL3AtoC+ALfAvkC5QL5AugC+QLuAvgC9QL2AvgC9QL+AvMCAQPyAgUD8QIIA/ACCwPvAg4D7QIQA+0CEgPsAhMD6wISA+oC/////8wCoQLOAqACzgKhAs8CoQLQAqIC0gKiAtUCogLXAqIC3AKgAuMCnQLmApwC7QKZAvACmAL2ApUC+AKUAvwCkwL9ApMC/gKTAv4ClAL9ApQC+wKUAv/////SAlEC0gJQAtMCUALUAk8C1gJOAtcCTgLZAk0C3gJLAuECSgLoAkcC8QJEAvUCQgL/Aj8CBwM9AgsDPAIOAzsCFAM7AhcDOwL/////eQMhAnkDIgJ6AyMCegMkAnoDJgJ6AycCewMrAnsDLAJ7AzECewMzAnwDOQJ8AzwCfANDAn0DRgJ9A0sCfgNUAn8DXgJ/A2QCfwNqAn8DdgJ+A4MCfgOPAn0DmwJ8A6ECfAOmAnsDsQJ6A7sCeQPAAnkDyQJ4A80CeAPRAngD2AJ4A90CeAPfAnkD4AJ7A+ECfQPhAn8D4AKEA98CjAPeApAD3QKUA9wCnQPcAqED2wKlA9sCrAPcArAD3AK0A90CtgPeArkD3wK6A98CuwPgAr0D4AK+A+AC//////4DTwL+A04C/gNNAv4DTAL/A0sC/wNKAgAESQIABEgCAQRIAgIESQIDBEwCAwRQAgMEVAIDBFkCAwRlAgMEbQICBH4CAgSHAgMEmgIDBKMCAwSrAgUEuwIFBMICBgTPAgcE1QIIBN4CCATiAggE5QIJBOkCCQTqAgsE6wIMBOsCEATqAhIE6gIXBOkCGgTpAh0E6AIgBOgCJgToAisE6AIwBOgCNQToAjcE6AI5BOgCPQToAv////+wBHMCsQRyArEEcQKxBG8CsQRtArEEawKxBGkCsARlAq8EZAKuBGICqwRgAqkEXwKkBGACogRhApwEZgKZBGoClgRuApAEegKNBIACiwSHAocEmAKFBKkChQS5AogEyAKKBM4CjQTTApUE3AKfBOACpATgAq8E3wK5BNwCwwTWAsoEzwLNBMsC0gTDAtQEvwLWBLUC1gSvAtcEpALXBJgC1gSNAtQEggLTBH0C0QR2As0EcQLMBG8CygRuAsYEbQLBBG8CvQRyArkEdQK4BHcCtgR6ArMEfgKyBIECsgSDArIEhQKzBIYC/////w==";
 
//Decode base64 string
$decodeddata=base64_decode($sigdata);
 
//Convert to integers from 0 to 255
$byte_array = unpack('C*',$decodeddata);
 
//Each coordinate is a set of two integers, so each point is a pair of two integers, that is, 4 integers. So, chunk the array into groups of 4
$byte_chunks=array_chunk($byte_array,4);
 
//Set up our array of points
$points=array();
$pointsx=array();
$pointsy=array();
 
//Loop through the chunks of 4 and do a reality check to make sure there are 4 in each
foreach($byte_chunks as $thispoint){
	if(count($thispoint)==4){
		//Convert the two integers into a single coordinate pair. The string, being small endian means that you multiply the first number by 256 and add the second number
		$thisx=$thispoint[0]+$thispoint[1]*256;
		$thisy=$thispoint[2]+$thispoint[3]*256;
		$points[]=array($thisx,$thisy);
	 
		if($thisx!=65535){
			$pointsx[]=$thisx;
		}
		if($thisy!=65535){
			$pointsy[]=$thisy;
		}
	}
}
 
//Keep track of the minimum and maximum coordinates
$minx=min($pointsx);
$miny=min($pointsy);
$maxx=max($pointsx);
$maxy=max($pointsy);
 
//Make image, scale to 512x512
$img=imagecreate($maxx-$minx,$maxy-$miny);
$white = imagecolorallocate($img, 255, 255, 255);
$black = imagecolorallocate($img, 0, 0, 0);
 
//Set our start point
$curx=0;
$cury=0;
 
//Set the state of our pen
$pendown=true;

foreach($points as $point){
	//Check for pen-up command, which is the point 65535,65535
	if($point[0]==65535 && $point[1]==65535){
		$pendown=false;
	}
	else{
		$pendown=true;
	}
	$newx=$point[0];
	$newy=$point[1];
 
	//Only draw if pen is down and we're not coming right back from a pen-up
	if($pendown && !($curx==65535 && $cury==65535)){
		imageline($img,$curx-$minx,$cury-$miny,$newx-$minx,$newy-$miny,$black);
	}
	$curx=$newx;
	$cury=$newy;
}

header('Content-Type: image/png');
imagepng($img);