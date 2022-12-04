<?php

/* _____ PHP Big Blue Button API Usage ______
* by Peter Mentzer peter@petermentzerdesign.com
* Use, modify and distribute however you like.
*/

// Require the bbb-api file:
require_once('../includes/bbb-api.php');


// Instatiate the BBB class:
$bbb = new BigBlueButton();

/* ___________ IS MEETING RUNNING? ______ */
/* Pass a meetingId to see if the meeting is currently running.
*/

$meetingId = $_POST['meetingID'];

$datojson = array();

// Get the URL to join meeting:
$itsAllGood = true;
try {$result = $bbb->isMeetingRunningWithXmlResponseArray($meetingId);}
	catch (Exception $e) {
		$datojson['status'] = 'ERROR';
		$datojson['mensaje'] = $e->getMessage();
		$itsAllGood = false;
	}

if ($itsAllGood == true) {
	//Output results to see what we're getting:
	$datojson['status'] = 'OK';
	$datojson['data'] = $result;
}	

echo json_encode($datojson);
?>