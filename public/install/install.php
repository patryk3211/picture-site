<?php
require __DIR__.'/../../utility/include.php';

function install(string $user, string $password) {
  if(strlen($user) > 64) {
    echo "Nazwa użytkownika jest za długa";
    return false;
  }
  if(strlen($user) == 0) {
    echo "Nazwa użytkownika nie może być pusta";
    return false;
  }
  $hash = password_hash($password, null);
  if(!$hash) {
    echo "Nieprawidłowe hasło";
    return false;
  }

  $db = database();
  $db->execute("CREATE TABLE ".DATABASE_TABLE_PREFIX."users(
    UserId int unsigned AUTO_INCREMENT,
    Username varchar(64) NOT NULL,
    Password varchar(255) NOT NULL,
    ChangePassword bool DEFAULT 0 NOT NULL,
    Perm_ManageUsers bool DEFAULT 0 NOT NULL,
    Perm_AddPictures bool DEFAULT 0 NOT NULL,
    Perm_DeletePictures bool DEFAULT 0 NOT NULL,
    PRIMARY KEY(UserId),
    CONSTRAINT UC_Username UNIQUE (Username)
  )");

  $db->execute("CREATE TABLE ".DATABASE_TABLE_PREFIX."picture_groups( 
    PictureGroupId int unsigned AUTO_INCREMENT,
    CreateTime DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Title varchar(255) NOT NULL,
    Description varchar(4096) NOT NULL DEFAULT '',
    UploadedBy int unsigned NOT NULL,
    PRIMARY KEY(PictureGroupId),
    CONSTRAINT FK_UploadedBy FOREIGN KEY(UploadedBy) REFERENCES ".DATABASE_TABLE_PREFIX."users(UserId)
  )");

  $db->execute("CREATE TABLE ".DATABASE_TABLE_PREFIX."pictures(
    PictureId int unsigned AUTO_INCREMENT,
    PictureGroupId int unsigned NOT NULL,
    FileName varchar(255) NOT NULL,
    PRIMARY KEY(PictureId),
    CONSTRAINT FK_PictureGroupId FOREIGN KEY(PictureGroupId) REFERENCES ".DATABASE_TABLE_PREFIX."picture_groups(PictureGroupId)
  )");

  $stmt = $db->prepare("INSERT INTO ".DATABASE_TABLE_PREFIX."users(Username, Password, Perm_ManageUsers, Perm_AddPictures, Perm_DeletePictures) VALUES (?, ?, 1, 1, 1);");
  $stmt->bind_param('ss', $user, $hash);
  $stmt->execute();

  return true;
}

if(!isset($_POST['username']) && !isset($_POST['password'])) {
  echo 'Podaj wymagane pola<br /><a href="./">Wróc</a>';
  die();
}

$username = $_POST['username'];
$password = $_POST['password'];

if(!install($username, $password)) {
  echo '<br /><a href="./">Wróc</a>';
  die();
}

echo 'Instalacja ukończona pomyślnie!<br /><a href="/">Dalej</a>';

