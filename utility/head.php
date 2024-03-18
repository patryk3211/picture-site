<?php
require __DIR__.'/endpoint.php';
if(!isset($noBootstrap) || !$noBootstrap) {
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<!--
<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/bootstrap/icons/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
-->
<?php
}

$prefix = URL_LOCATION_PREFIX;
if(!isset($validateSession) || $validateSession)
  echo "<script src=\"$prefix/js/auth.js\"></script>";
echo <<<HTML
<link href="$prefix/css/style.css" rel="stylesheet" />
<script src="$prefix/js/main.js"></script>
HTML;
?>

