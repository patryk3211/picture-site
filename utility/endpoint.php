<?php
// Doesn't matter here
require __DIR__.'/config.php';
$apiEndpoint = APP_URL.URL_LOCATION_PREFIX.'/api';
$urlPrefix = URL_LOCATION_PREFIX;
echo "<script>const API_ENDPOINT = '$apiEndpoint'; const URL_PREFIX = '$urlPrefix';</script>";

