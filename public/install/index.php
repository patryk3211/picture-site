<?php
require __DIR__.'/../../utility/include.php';
$db = database();

$stmt = $db->prepare('SHOW TABLES;');
if(!$stmt->execute()) {
  http_response_code(500);
  die();
}

$result = $stmt->get_result();
if($result->num_rows > 0) {
  http_response_code(403);
  echo "<h1>403 - Forbidden</h1>";
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Installation Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <h1>Tworzenie użytkownika</h1>
    <form action="install.php" method="post">
      Nazwa użytkownika:
      <input type="text" name="username" maxlength="64" required /><br />
      Hasło:
      <input type="password" name="password" required /><br />
      <input type="submit" value="Dalej" />
    </form>
  </body>
</html>

