<?php

class AdminApiController {
  const PERMISSION_MANAGE_USERS = 'ManageUsers';
  const PERMISSION_ADD_PICTURES = 'AddPictures';
  const PERMISSION_DELETE_PICTURES = 'DeletePictures';

  public bool $json = true;
  public bool $get = false;
  public bool $post = true;

  public function auth($json) {
    if(!isset($json->user) || $json->user == '') {
      return response_json([ 'result' => false, 'message' => 'Podaj nazwę użytkownika' ]);
    }
    if(!isset($json->password) || $json->password == '') {
      return response_json([ 'result' => false, 'message' => 'Podaj hasło' ]);
    }

    $user = new User($json->user, $json->password);

    $result = $user->auth_user();
    $json = [ 'result' => $result ];
    if($result) {
      if(!$user->fetch())
        return response_code(500);
      if($user->changePassword) {
        $json['result'] = false;
        $json['changepassword'] = true;
      } else {
        if(!session_start())
          return response_code(500);
        $id = session_id();
        if(!$id)
          return response_code(500);
        $json['session'] = $id;
        $_SESSION['authenticated'] = true;
        $_SESSION['permissions'] = $user->permissions;
        $_SESSION['user_id'] = $user->id;
        if(!session_commit())
          return response_code(500);
      }
    } else {
      $json['message'] = 'Nieprawidłowa nazwa użytkownika albo hasło';
    }

    return response_json($json);
  }

  public function change_password($json) {
    if(!isset($json->user) || !isset($json->password) || !isset($json->newpassword)) {
      return response_code(400);
    }
    $user = new User($json->user, $json->password);
    if($user->auth_user()) {
      $result = $user->change_password($json->newpassword);
      return response_json([ 'result' => $result ]);
    } else {
      return response_code(401);
    }
  }

  private static function check_auth($json): Response|null {
    if(!isset($json->session))
      return response_code(400);
    session_id($json->session);
    if(!session_start())
      return response_code(500);
    if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
      session_destroy();
      return response_code(401);
    }
    // Authenticated
    return null;
  }

  private static function check_permission(string $permission): bool {
    return $_SESSION['permissions'][$permission];
  } 

  public function validate_session($json): Response {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    return response_code(200);
  }

  public function users($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_MANAGE_USERS)) {
      // Not permitted
      return response_code(401);
    }

    $db = database();
    $stmt = $db->prepare("SELECT UserId, Username, ChangePassword, Perm_ManageUsers, Perm_AddPictures, Perm_DeletePictures FROM ".DATABASE_TABLE_PREFIX."users");
    if(!$stmt->execute())
      return response_code(500);
    $result = $stmt->get_result();

    $json = [];
    while($row = $result->fetch_assoc()) {
      array_push($json, [
        "id" => $row["UserId"],
        "username" => $row["Username"],
        "changepassword" => $row["ChangePassword"],
        "manageusers" => $row["Perm_ManageUsers"],
        "addpictures" => $row["Perm_AddPictures"],
        "deletepictures" => $row["Perm_DeletePictures"],
      ]);
    }

    return response_json($json);
  }

  public function create_user($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_MANAGE_USERS)) {
      // Not permitted
      return response_code(401);
    }

    if(!isset($json->username) || $json->username == '') {
      return response_json([ 'result' => false, 'message' => 'Podaj nazwę użytkownika' ]);
    }
    if(!isset($json->password) || $json->password == '') {
      return response_json([ 'result' => false, 'message' => 'Podaj hasło' ]);
    }

    $user = new User($json->username, $json->password);
    $response = $user->create();
    if($user->id != null) {
      if(isset($json->changepassword) && $json->changepassword)
        $user->set_password_change();

      $perms = [];
      if(isset($json->manageusers)) {
        $perms['Perm_ManageUsers'] = $json->manageusers ? 1 : 0;
      }
      if(isset($json->addpictures)) {
        $perms['Perm_AddPictures'] = $json->addpictures ? 1 : 0;
      }
      if(isset($json->deletepictures)) {
        $perms['Perm_DeletePictures'] = $json->deletepictures ? 1 : 0;
      }

      $user->set_permissions($perms);
    }
    return $response;
  }

  public function delete_user($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_MANAGE_USERS)) {
      // Not permitted
      return response_code(401);
    }

    if(!isset($json->userid)) {
      return response_code(400);
    }

    $user = User::from_id($json->userid);
    if($user instanceof Response)
      return $user;
    return response_json([ 'result' => $user->delete() ]);
  }

  public function logout($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    $result = session_destroy();
    return response_json([ 'result' => $result ]);
  }

  public function upload_pictures($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_ADD_PICTURES)) {
      // Not permitted
      return response_code(401);
    }

    if(!isset($json->title) || !isset($json->images) || !is_array($json->images)) {
      return response_code(400);
    }

    if(strlen($json->title) > 255) {
      return response_code(413);
    }

    if(isset($json->description) && strlen($json->description) > 4096) {
      return response_code(413);
    }

    // Decode and save images
    $images = [];
    foreach($json->images as $image) {
      if(!is_string($image))
        return response_code(400);
      $headerPos = strpos($image, ';base64,');
      $dataStart = $headerPos + strlen(';base64,');
      $mimeType = substr($image, strlen('data:'), $headerPos - strlen('data:'));
      $data = substr($image, $dataStart);

      $ext = '';
      switch($mimeType) {
        case 'image/png':
          $ext = '.png';
          break;
        case 'image/jpeg':
          $ext = '.jpeg';
          break;
        case 'image/gif':
          $ext = '.gif';
          break;
        case 'image/avif':
          $ext = '.avif';
          break;
        case 'image/tiff':
          $ext = '.tiff';
          break;
        case 'image/webp':
          $ext = '.webp';
          break;
        default:
          return response_code(415);
      }

      $decoded = base64_decode($data);
      if(!$decoded)
        return response_code(400);

      array_push($images, [ 'ext' => $ext, 'bin' => $decoded ]);
    }

    $filenames = [];
    foreach($images as $image) {
      $filename = '';
      $filepath = '';
      do {
        $filename = uniqid('img', true).$image['ext'];
        $filepath = PICTURE_STORAGE_LOCATION.'/'.$filename;
      } while(file_exists($filepath));
      file_put_contents($filepath, $image['bin']);
      array_push($filenames, $filename);
    }

    $db = database();
    $stmt = $db->prepare('INSERT INTO '.DATABASE_TABLE_PREFIX."picture_groups(Title, Description, UploadedBy) VALUES (?, ?, ?)");
    $description = isset($json->description) ? $json->description : '';
    $stmt->bind_param('ssi', $json->title, $description, $_SESSION['user_id']);
    if(!$stmt->execute()) {
      return response_code(500);
    }
    $groupId = $stmt->insert_id;

    $stmt = $db->prepare('INSERT INTO '.DATABASE_TABLE_PREFIX."pictures(PictureGroupId, FileName) VALUES (?, ?)".str_repeat(',(?, ?)', count($images) - 1));
    $stmt_types = str_repeat('is', count($images));
    $stmt_params = [];
    foreach($filenames as $image) {
      array_push($stmt_params, $groupId, $image);
    }
    $stmt->bind_param($stmt_types, ...$stmt_params);
    if(!$stmt->execute()) {
      return response_code(500);
    }

    return response_code(200);
  }

  public function delete_pictures($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_DELETE_PICTURES)) {
      // Not permitted
      return response_code(401);
    }

    if(!isset($json->groupid)) {
      return response_code(400);
    }

    $db = database();
    $stmt = $db->prepare('SELECT FileName FROM '.DATABASE_TABLE_PREFIX.'pictures WHERE PictureGroupId = ?;');
    $stmt->bind_param('i', $json->groupid);
    if(!$stmt->execute()) {
      return response_code(500);
    }

    $result = $stmt->get_result();
    while($row = $result->fetch_row()) {
      $filename = $row[0];
      $filepath = PICTURE_STORAGE_LOCATION.'/'.$filename;
      unlink($filepath);
    }


    $stmt = $db->prepare('DELETE FROM '.DATABASE_TABLE_PREFIX.'pictures WHERE PictureGroupId = ?;');
    $stmt->bind_param('i', $json->groupid);
    if(!$stmt->execute()) {
      return response_code(500);
    }

    $stmt = $db->prepare('DELETE FROM '.DATABASE_TABLE_PREFIX.'picture_groups WHERE PictureGroupId = ?;');
    $stmt->bind_param('i', $json->groupid);
    if(!$stmt->execute()) {
      return response_code(500);
    }

    return response_code(200);
  }

  public function all_posts($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    $db = database();
    $stmt = $db->prepare(
     'SELECT G.PictureGroupId, G.Title, G.Description, U.Username, Count(*) AS ImageCount
      FROM '.DATABASE_TABLE_PREFIX.'picture_groups AS G
      INNER JOIN '.DATABASE_TABLE_PREFIX.'pictures AS P ON G.PictureGroupId = P.PictureGroupId
      INNER JOIN '.DATABASE_TABLE_PREFIX.'users AS U ON G.UploadedBy = U.UserId
      GROUP BY G.PictureGroupId;');
    if(!$stmt->execute())
      return response_code(500);

    $response = [];
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      array_push($response, [
        'id' => $row['PictureGroupId'],
        'title' => $row['Title'],
        'description' => $row['Description'],
        'uploader' => $row['Username'],
        'imagecount' => $row['ImageCount'],
      ]);
    }

    return response_json($response);
  }

  public function admin_change_password($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_MANAGE_USERS)) {
      // Not permitted
      return response_code(401);
    }

    if(!isset($json->userid) || !isset($json->password)) {
      return response_code(400);
    }

    $user = User::from_id($json->userid);
    if($user instanceof Response)
      return $user;

    $change = false;
    if(isset($json->changepassword) && $json->changepassword)
      $change = true;
    if(!$user->change_password($json->password, $change))
      return response_code(500);
    return response_code(200);
  }

  public function change_permissions($json) {
    $response = AdminApiController::check_auth($json);
    if($response != null) {
      // Not authenticated
      return $response;
    }

    if(!AdminApiController::check_permission(AdminApiController::PERMISSION_MANAGE_USERS)) {
      // Not permitted
      return response_code(401);
    }

    if(!isset($json->userid)) {
      return response_code(400);
    }
    
    if($_SESSION['user_id'] == $json->userid) {
      // Cannot change our own permissions
      return response_code(401);
    }

    $perms = [];
    if(isset($json->manageusers)) {
      $perms['Perm_ManageUsers'] = $json->manageusers ? 1 : 0;
    }
    if(isset($json->addpictures)) {
      $perms['Perm_AddPictures'] = $json->addpictures ? 1 : 0;
    }
    if(isset($json->deletepictures)) {
      $perms['Perm_DeletePictures'] = $json->deletepictures ? 1 : 0;
    }
    if(count($perms) == 0)
      return response_code(401);

    $user = User::from_id($json->userid);
    if($user instanceof Response)
      return $user;
    if(!$user->set_permissions($perms))
      return response_code(500);
    return response_code(200);
  }
}

