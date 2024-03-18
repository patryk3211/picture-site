<?php
class User {
  public ?int $id = null;
  public string $username;
  public ?string $password;

  public ?array $permissions = null;
  public ?bool $changePassword = null;

  public function __construct(string $name, ?string $pass) {
    $this->username = $name;
    $this->password = $pass;
  }

  public static function from_id(int $id): User|Response {
    $db = database();
    $stmt = $db->prepare("SELECT Username FROM ".DATABASE_TABLE_PREFIX."users WHERE UserId = ?");
    $stmt->bind_param('i', $id);
    if(!$stmt->execute())
      return response_code(500);
    $result = $stmt->get_result();
    if($result->num_rows == 0)
      return response_code(400);

    $user = new User($result->fetch_assoc()['Username'], null);
    $user->id = $id;
    return $user;
  }

  public function create(): Response {
    if($this->password == null)
      return response_code(400);
    $db = database();
    $hash = password_hash($this->password, null);
    if(!$hash)
      return response_code(500);
    $stmt = $db->prepare("INSERT INTO ".DATABASE_TABLE_PREFIX."users(Username, Password) VALUES (?, ?)");
    $stmt->bind_param('ss', $this->username, $hash);
    try {
    if($stmt->execute()) {
      $this->id = $stmt->insert_id;
      return response_json([ 'result' => true ]);
    }
    } catch(mysqli_sql_exception $e) {
      if($e->getCode() == 1062) {
        // Non unique username
        return response_json([ 'result' => false, 'message' => 'Nazwa użytkownika nie jest unikatowa' ]);
      } else {
        // Rethrow the exception
        throw $e;
      }
    }
    return response_code(500);
  }

  public function delete(): bool {
    if($this->id == null)
      return false;
    $db = database();
    $stmt = $db->prepare("DELETE FROM ".DATABASE_TABLE_PREFIX."users WHERE UserId = ?;");
    $stmt->bind_param('i', $this->id);
    return $stmt->execute();
  }

  public function set_password_change(): bool {
    if($this->id == null)
      return false;
    $db = database();
    $stmt = $db->prepare("UPDATE ".DATABASE_TABLE_PREFIX."users SET ChangePassword = 1 WHERE UserId = ?;");
    $stmt->bind_param('i', $this->id);
    return $stmt->execute();
  }

  public function auth_user(): bool {
    $db = database();
    $stmt = $db->prepare("SELECT UserId, Password FROM ".DATABASE_TABLE_PREFIX."users WHERE Username = ?;");
    $stmt->bind_param('s', $this->username);
    if(!$stmt->execute()) {
      http_response_code(500);
      die();
    }
    $result = $stmt->get_result();
    if($result->num_rows == 0)
      return false;
    $row = $result->fetch_assoc();

    $this->id = $row['UserId'];
    $passHash = $row['Password'];
    return password_verify($this->password, $passHash);
  }

  public function change_password(string $newPassword, ?bool $changeAfterLogin = null): bool {
    if($this->id == null)
      return false;
    $hash = password_hash($newPassword, null);
    if(!$hash)
      return false;

    $db = database();
    $stmt = $db->prepare("UPDATE ".DATABASE_TABLE_PREFIX."users SET Password = ?, ChangePassword = ? WHERE UserId = ?");
    $change = $changeAfterLogin ? 1 : 0;
    $stmt->bind_param('sii', $hash, $change, $this->id);
    return $stmt->execute();
  }

  public function set_permissions(array $permissions): bool {
    if($this->id == null)
      return false;

    $db = database();
    $query = "UPDATE ".DATABASE_TABLE_PREFIX."users SET ";
    $first = true;
    foreach($permissions as $name => $value) {
      if(!$first)
        $query .= ", ";
      $query .= "$name = $value";
      $first = false;
    }
    $query .= " WHERE UserId = ".$this->id;
    return $db->execute($query);
  }

  public function fetch(): bool {
    if(!$this->id)
      return false;
    
    $db = database();
    $stmt = $db->prepare("SELECT Perm_ManageUsers, Perm_AddPictures, Perm_DeletePictures, ChangePassword FROM ".DATABASE_TABLE_PREFIX."users WHERE UserId = ?;");
    $stmt->bind_param('i', $this->id);
    if(!$stmt->execute())
      return false;
    $result = $stmt->get_result();
    if($result->num_rows == 0)
      return false;

    $row = $result->fetch_assoc();
    $permArray = [];
    foreach($row as $perm => $value) {
      if(strpos($perm, 'Perm_') != 0)
        continue;
      $permArray[substr($perm, 5)] = $value == 0 ? false : true;
    }

    $this->changePassword = $row['ChangePassword'];
    $this->permissions = $permArray;
    return true;
  }
}

