<?php
require_once __DIR__.'/../../utility/include.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(strpos($uri, URL_LOCATION_PREFIX.'/api') != 0) {
  http_response_code(400);
  die();
}

const HANDLERS = [
  "/auth" => [ AdminApiController::class, "auth" ],
  "/changepass" => [ AdminApiController::class, "change_password" ],
  "/check" => [ AdminApiController::class, "validate_session" ],
  "/logout" => [ AdminApiController::class, "logout" ],
  "/users" => [ AdminApiController::class, "users" ],
  "/user/create" => [ AdminApiController::class, "create_user" ],
  "/user/delete" => [ AdminApiController::class, "delete_user" ],
  "/upload" => [ AdminApiController::class, "upload_pictures" ],
  "/pictures/all" => [ AdminApiController::class, "all_posts" ],
  "/pictures/delete" => [ AdminApiController::class, 'delete_pictures' ],

  "/pictures" => [ PublicApiController::class, "fetch_pictures" ],
  "/post_count" => [ PublicApiController::class, "post_count" ],
];

$uri = substr($uri, strlen(URL_LOCATION_PREFIX.'/api'));

if(!isset(HANDLERS[$uri])) {
  http_response_code(404);
  die();
}

$arg0 = null;

$entry = HANDLERS[$uri];
$controller = new $entry[0]();

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
  case 'GET':
    if(!isset($controller->get) || !$controller->get) {
      http_response_code(405);
      die();
    }
    break;
  case 'POST':
    if(!isset($controller->post) || !$controller->post) {
      http_response_code(405);
      die();
    }
    break;
  default:
    http_response_code(405);
    die();
}

if(isset($controller->json) && $controller->json) {
  if($_SERVER['CONTENT_TYPE'] != 'application/json') {
    http_response_code(400);
    die();
  }

  $postBody = file_get_contents('php://input');
  $postJson = json_decode($postBody);
  if($postJson == null) {
    http_response_code(400);
    die();
  }
  $arg0 = $postJson;
}

$funcName = $entry[1];

$response = $controller->$funcName($arg0);
if($response != null) {
  $response->send();
}

