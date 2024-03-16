<?php
// Doesn't matter here
require __DIR__.'/config.php';
$apiEndpoint = APP_URL.URL_LOCATION_PREFIX.'/api';
echo "<script>const API_ENDPOINT = '$apiEndpoint';</script>";

