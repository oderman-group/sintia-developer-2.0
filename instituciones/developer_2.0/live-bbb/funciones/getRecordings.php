<?php

/* _____ PHP Big Blue Button API Usage ______
* by Peter Mentzer peter@petermentzerdesign.com
* Use, modify and distribute however you like.
*/

// Require the bbb-api file:
require_once('../includes/bbb-api.php');


// Instatiate the BBB class:
$bbb = new BigBlueButton();

/* ___________ GET RECORDINGS INFO ______ */
/* Get recordings info based on optional meeting id, or all.
*/
$meetingId = $_POST['meetingID'];
$datojson = array();

$recordingsParams = array(
	'meetingId' => $meetingId, 			// OPTIONAL - comma separate if multiples
);

// Now get recordings info and display it:
$itsAllGood = true;
try {$result = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);}
	catch (Exception $e) {
		$datojson['status'] = 'ERROR';
		$datojson['mensaje'] = $e->getMessage();
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
				$datojson['status'] = 'OK';
				$datojson['data'] = $result;
			}
			else {
				$datojson['status'] = 'ERROR';
				$datojson['mensaje'] = 'Error al obtener informacion de la conferencia';
			}
		}
	}	

	echo json_encode($datojson);
?>