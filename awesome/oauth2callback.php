<?php
require_once __DIR__.'/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $drive_service = new Google_Service_Drive($client);
  $files_list = $drive_service->files->listFiles(array())->getFiles();
  // echo json_encode($files_list);
} else {
  // $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
  // header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	echo "This is where I can put info i guess";

	// Get the API client and construct the service object.
	$client = getClient();
	$service = new Google_Service_Sheets($client);

	$spreadsheetId = '1glCC2-zAhAGgqeIhrh8VtsEiNzFHqHMVc8BD48TOj4c';
	$range = 'Names Data!A2:B';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$values = $response->getValues();

	//update rows--first [] = row, second []
	$values[0][0] = 'Updated';
	$values[0][1] = 'Row'; 
	$body = new Google_Service_Sheets_ValueRange(array(
	  'values' => $values
	));
	$params = array(
	  'valueInputOption' => "RAW"
	);
	$result = $service->spreadsheets_values->update($spreadsheetId, $range,
	    $body, $params);

	//clear specified row(s)--deletes but leaves gap
	$values[1][0] = '';
	$values[1][1] = ''; 
	$body = new Google_Service_Sheets_ValueRange(array(
	  'values' => $values
	));
	$params = array(
	  'valueInputOption' => "RAW"
	);
	$result = $service->spreadsheets_values->update($spreadsheetId, $range,
	    $body, $params);

	//append rows to end of the current data--have to leave this at end or can't clear specified row--can't find the number
	$values = array(
	    array('Appended', 'Row')
	    // Additional rows ...
	);
	$body = new Google_Service_Sheets_ValueRange(array(
	  'values' => $values
	));
	$params = array(
	  'valueInputOption' => "RAW"
	);
	$result = $service->spreadsheets_values->append($spreadsheetId, $range,
	    $body, $params);
}


?>  