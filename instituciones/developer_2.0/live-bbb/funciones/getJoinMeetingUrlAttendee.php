<?php

/* _____ PHP Big Blue Button API Usage ______
* by Peter Mentzer peter@petermentzerdesign.com
* Use, modify and distribute however you like.
*/

// Require the bbb-api file:
require_once('../includes/bbb-api.php');


// Instatiate the BBB class:
$bbb = new BigBlueButton();

// Variables
$meetingID = $_POST['meetingID'];
$attendeePW = $_POST['attendeePW'];
$username = $_POST['username'];

$datojson = array();

/* ___________ JOIN MEETING w/ OPTIONS ______ */
/* Determine the meeting to join via meetingId and join it.
*/

$joinParams = array(
	'meetingId' => $meetingID, 			// REQUIRED - We have to know which meeting to join.
	'username' => $username,	// REQUIRED - The user display name that will show in the BBB meeting.
	'password' => $_POST['attendeePW'],				// REQUIRED - Must match either attendee or moderator pass for meeting.
	'createTime' => '',				// OPTIONAL - string
	'userId' => '',					// OPTIONAL - string
	'webVoiceConf' => ''			// OPTIONAL - string
);

// Get the URL to join meeting:
$itsAllGood = true;
try {$result = $bbb->getJoinMeetingURL($joinParams);}
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