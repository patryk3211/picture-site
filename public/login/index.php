<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>Logowanie</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $validateSession = false; require __DIR__.'/../../utility/head.php'; ?>
    <script src="login.js"></script>
  </head>
  <body class="bg-dark">
    <?php $noLogin = false; require __DIR__.'/../../utility/nav.php' ?>
    <main class="main bg-light container">
      <header><h2>Logowanie</h2></header>
      <form id="loginform" onsubmit="event.preventDefault()">
        <div class="form-group mb-1">
          <label for="username">Nazwa użytkownika</label>
          <div class="input-group">
            <span class="input-group-text" id="user-addon"><i class="bi bi-person-fill"></i></span>
            <input class="form-control" type="text" name="username" id="username" aria-describedby="user-addon" />
          </div>
        </div>
        <div class="form-group mb-1">
          <label for="password">Hasło</label>
          <div class="input-group">
            <span class="input-group-text" id="password-addon"><i class="bi bi-key-fill"></i></span>
            <input class="form-control" type="password" name="password" id="password" aria-describedby="password-addon" />
          </div>
        </div>
        <div id="loginalert" class="alert alert-danger collapse" role="alert">
        </div>
        <input class="submit btn btn-primary" type="submit" value="Zaloguj" />
      </form>
    </main>
  </body>
</html>

