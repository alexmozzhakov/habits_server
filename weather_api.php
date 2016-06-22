<?php
$start = microtime(true);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "ip-api.com/json/" . $_SERVER['REMOTE_ADDR']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$locationData = json_decode(curl_exec($ch));
curl_close($ch);
$yql_query = 'SELECT item.condition.temp FROM weather.forecast WHERE woeid IN (SELECT woeid FROM geo.places(1) WHERE TEXT="' . $locationData->city . ', ' . $locationData->country . '")AND u="c"';
$yql_query_url = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql_query) . "&format=json";
$session = curl_init($yql_query_url);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($session);
curl_close($session);
$arr = json_decode($json);
$c = $arr->query->results->channel->item->condition->temp;
// if ($c != 0) {
print_r('{"celsius" : "' . $c . '","location" : "' . $locationData->city . ', ' . $locationData->country . '"');
// } else print_r('{"error" : "Can\'t get weather"');
$time_elapsed_secs = (microtime(true) - $start) * 100;
print_r(', "time_elapsed":"' . $time_elapsed_secs . ' ms" }');