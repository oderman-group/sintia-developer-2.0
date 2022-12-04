<?php

/* _____ PHP Big Blue Button API Usage ______
* by Peter Mentzer peter@petermentzerdesign.com
* Use, modify and distribute however you like.
*/

// Require the bbb-api file:
require_once('../includes/bbb-api.php');

// Instatiate the BBB class:
$bbb = new BigBlueButton();

/* ___________ GET MEETINGS FROM BBB SERVER ______ */
/* 
*/

/* 
---DEBUG - useful for manually checking the raw xml results.
$test = $bbb->getGetMeetingsUrl();
echo $test;
 ---END DEBUG 
*/

$datojson = array();

$itsAllGood = true;
try {$result = $bbb->getMeetingsWithXmlResponseArray();}
	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
		$itsAllGood = false;
	}

if ($itsAllGood == true) {
	// If it's all good, then we've interfaced with our BBB php api OK:
	if ($result == null) {
		// If we get a null response, then we're not getting any XML back from BBB.
		$datojson['status'] = 'ERROR';
		$datojson['mensaje'] = 'Error al al obtener la respuesta, revisa el estado del servidor';
	}	
	else { 
	// We got an XML response, so let's see what it says:
		if ($result['returncode'] == 'SUCCESS') {
			// Then do stuff ...
			// You can parse this array how you like. For now we just do this:
			$datojson['status'] = 'OK';
			$datojson['data'] = $result;
		}
		else {
			echo "<p>We didn't get a success response. Instead we got this:</p>";
			$datojson['status'] = 'ERROR';
			$datojson['mensaje'] = 'Error al obtener respuesta en la peticion';
		}
	}
}

echo json_encode($datojson);

?>