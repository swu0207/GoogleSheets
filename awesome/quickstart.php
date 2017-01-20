<?php
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('America/Los_Angeles');


  
define('APPLICATION_NAME', 'Google Sheets API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/sheets.googleapis.com-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
// define('SCOPES', implode(' ', array(
//   Google_Service_Sheets::SPREADSHEETS_READONLY)
// ));
define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS)
));

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}
 
/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    // $refreshToken = $accessToken;
    // $token['refresh_token'] = $accessToken;


    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $refreshToken = $client->getRefreshToken();
    $client->refreshToken($refreshToken);
    $newAccessToken = $client->getAccessToken();
    $newAccessToken['refresh_token'] = $refreshToken;
    file_put_contents($credentialsPath, json_encode($newAccessToken));
    // $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    // file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets($client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1glCC2-zAhAGgqeIhrh8VtsEiNzFHqHMVc8BD48TOj4c/edit#gid=0
$spreadsheetId = '1glCC2-zAhAGgqeIhrh8VtsEiNzFHqHMVc8BD48TOj4c';
$range = 'Names Data!A2:B';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

if (count($values) == 0) {
  print "No data found.\n";
} else {
  print "Before:\n";
  foreach ($values as $row) {
    // Print columns A and E, which correspond to indices 0 and 4.
    printf("%s %s\n", $row[0], $row[1]);
  }
}

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

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
// https://docs.google.com/spreadsheets/d/1glCC2-zAhAGgqeIhrh8VtsEiNzFHqHMVc8BD48TOj4c/edit#gid=0
$spreadsheetId = '1glCC2-zAhAGgqeIhrh8VtsEiNzFHqHMVc8BD48TOj4c';
$range = 'Names Data!A2:B';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

if (count($values) == 0) {
  print "No data found.\n";
} else {
  print "After:\n";
  foreach ($values as $row) {
    // Print columns A and E, which correspond to indices 0 and 4.
    printf("%s %s\n", $row[0], $row[1]);
  }
}
