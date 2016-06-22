<?php
if (isset($_GET['lon']) && isset($_GET['lat'])) {
    $_GET['lon'];
    $locationData = $arr->query->results->channel->location;
    $yql_query = 'select item.condition.temp from weather.forecast where woeid in (select woeid from geo.places(1) where text="' . $locationData->city . ', ' . $locationData->country . '")and u="c"';

} else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "ip-api.com/json/" . $_SERVER['REMOTE_ADDR']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $locationData = json_decode(curl_exec($ch));
    curl_close($ch);
    $yql_query = 'select item.condition.temp from weather.forecast where woeid in (select woeid from geo.places(1) where text="' . $locationData->city . ', ' . $locationData->country . '")and u="c"';
}

$session = curl_init($yql_query_url);
$yql_query_url = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode($yql_query) . "&format=json";
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($session);
curl_close($session);
$arr = json_decode($json);
$c = $arr->query->results->channel->item->condition->temp;
if ($c != 0) {
    print_r('{"celsius" : "' . $c . '","location" : "' . $locationData->city . ', ' . $locationData->country . '"}');
} else print_r('{"error" : "Can\'t get weather"}');