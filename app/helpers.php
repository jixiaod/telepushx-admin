<?php    

function _post($url, array $data, $type = 'form-data', $header = [], $closeSSl = true)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  if (substr_count($url, 'https://') > 0 && $closeSSl) {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  }
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_POST, 1);
  if ($type == 'json') {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($header, ['Content-Type: application/x-www-form-urlencoded', "accept: application/json"]));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  } else {
    if ($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  }
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}

function _get($url)
{
  $curl_handle=curl_init();
  curl_setopt($curl_handle, CURLOPT_URL,$url);
  curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
  $file = curl_exec($curl_handle);
  curl_close($curl_handle);
  $res = json_decode($file,true);
  return $res['data'];
}

function myToArray($objArray) {
  $arr = [];
  foreach ($objArray as $obj) {
    $arr[] = json_decode(json_encode ( $obj ) , true);
  } 

  return $arr;
}

function convertToMinutes($time)
{
  list($hours, $minutes, $seconds) = explode(':', $time);
  return ($hours * 60) + $minutes;
}

