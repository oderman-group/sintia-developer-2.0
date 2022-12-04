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
$moderatorPW = $_POST['moderatorPW'];
$attendeePW = $_POST['attendeePW'];
$meetingName = $_POST['meetingName'];
$username = $_POST['username'];

$datojson = array();

/* ___________ CREATE MEETING w/ OPTIONS ______ */
/* 
*/
$creationParams = array(
	'meetingId' => $meetingID, 					// REQUIRED
	'meetingName' => $meetingName, 	// REQUIRED
	'attendeePw' => $attendeePW, 					// Match this value in getJoinMeetingURL() to join as attendee.
	'moderatorPw' => $moderatorPW, 					// Match this value in getJoinMeetingURL() to join as moderator.
	'welcomeMsg' => 'Bienvenido_a_'.$meetingName, 					// ''= use default. Change to customize.
	'dialNumber' => '', 					// The main number to call into. Optional.
	'voiceBridge' => '', 					// PIN to join voice. Optional.
	'webVoice' => '', 						// Alphanumeric to join voice. Optional.
	'logoutUrl' => 'https://plataformasintia.com/', 						// Default in bigbluebutton.properties. Optional.
	'maxParticipants' => '-1', 				// Optional. -1 = unlimitted. Not supported in BBB. [number]
	'record' => 'true', 					// New. 'true' will tell BBB to record the meeting.
	'duration' => '0', 						// Default = 0 which means no set duration in minutes. [number]
	//'meta_category' => '', 				// Use to pass additional info to BBB server. See API docs.
);

// Create the meeting and get back a response:
$itsAllGood = true;
try {$result = $bbb->createMeetingWithXmlResponseArray($creationParams);}
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
		$datojson['mensaje'] = "Fallo la peticion get, revise el estado del servidor";
	}	
	else { 
	// We got an XML response, so let's see what it says:
	// print_r($result);
		if ($result['returncode'] == 'SUCCESS') {
			// Then do stuff ...
		  


			/* ___________ JOIN MEETING w/ OPTIONS ______ */
			/* Determine the meeting to join via meetingId and join it.
			*/

			$joinParams = array(
				'meetingId' => $meetingID, 				// REQUIRED - We have to know which meeting to join.
				'username' => $username,		// REQUIRED - The user display name that will show in the BBB meeting.
				'password' => $moderatorPW,					// REQUIRED - Must match either attendee or moderator pass for meeting.
				'createTime' => '',					// OPTIONAL - string
				'userId' => '',						// OPTIONAL - string
				'webVoiceConf' => ''				// OPTIONAL - string
			);

			// Get the URL to join meeting:
		
			try {$result2 = $bbb->getJoinMeetingURL($joinParams);}
				catch (Exception $e) {
					$datojson['status'] = 'ERROR';
					$datojson['mensaje'] = $e->getMessage();
					$itsAllGood = false;
				}

			if ($itsAllGood == true) {
				//Output results to see what we're getting:
				// print_r($result2);
				$datojson['status'] = 'OK';
				$datojson['data'] = $result2;
			}	
		}
		
	}
}

echo json_encode($datojson);

?>