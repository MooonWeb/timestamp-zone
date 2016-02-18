function getGPS($adresse){
  $adresse = str_replace(' ', '+', $adresse);
  $url = "http://maps.google.com/maps/api/geocode/json?address=".$adresse;
  $response = url_get_contents($url);
  $response = json_decode($response, true);
  
  $lat = $response['results'][0]['geometry']['location']['lat'];
  $long = $response['results'][0]['geometry']['location']['lng'];
  return array($lat, $long);
}

function getTimeZoneStamp($lat, $long){
  $url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.$lat.','.$long.'&timestamp='.time().'&sensor=false';
  $response = url_get_contents($url);
  $response = json_decode($response, true);
  $timeStamp = $response['rawOffset'];
  $timeZoneIs = $response['timeZoneId'];
  return array($timeStamp, $timeZoneIs);
}

function dateTimeDestStamp($dateTime = '', $adresse = '', $codePostal = '', $localite = '', $province = '', $pays = ''){
	$adresseGPS = $adresse . ' ' . $codePostal . ' ' . $localite . ' ' . $province . ' ' . $pays;
	$gps = getGPS($adresseGPS);
	$latitude = $gps[0];
	$longitude = $gps[1];
	$timeZone = getTimeZoneStamp($latitude, $longitude);
	$timeZoneStamp = $timeZone[0];
	$fuseau = $timeZone[1];
	// $dateTime format = dd/mm/yyyy hh:mm (ex : 12/05/2020 12:15)
	$d = substr($dateTime, 0, 2);
	$m = substr($dateTime, 3, 2);
	$Y = substr($dateTime, 6, 4);
	$H = substr($dateTime, 11, 2);
	$min = substr($dateTime, 14, 2);
	$dateTime = mktime($H, $min, '00', $m, $d, $Y) + $timeZoneStamp - ( date_default_timezone_set('Europe/Brussels') * 3600 );
	return $dateTime;
}
