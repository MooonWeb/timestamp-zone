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
