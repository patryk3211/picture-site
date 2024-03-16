<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>Zmiana hasła</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $validateSession = false; require __DIR__.'/../../utility/head.php'; ?>
    <script src="script.js"></script>
  </head>
  <body class="bg-dark">
    <?php $noLogin = false; require __DIR__.'/../../utility/nav.php' ?>
    <main class="main bg-light container">
      <header><h2>Zmiana hasła</h2></header>
      <form id="passwordform" onsubmit="event.preventDefault()">
        <div class="form-group mb-1">
          <label for="username">Nazwa użytkownika</label>
          <div class="input-group">
            <span class="input-group-text" id="user-addon"><i class="bi bi-person-fill"></i></span>
            <input class="form-control" type="text" name="username" id="username" aria-describedby="user-addon" />
          </div>
        </div>
        <div class="form-group mb-1">
          <label for="oldpassword">Stare hasło</label>
          <div class="input-group">
            <span class="input-group-text" id="password-addon"><i class="bi bi-key-fill"></i></span>
            <input class="form-control" type="password" name="oldpassword" id="oldpassword" aria-describedby="password-addon" />
          </div>
        </div>
        <div class="form-group mb-2">
          <label for="newpassword">Nowe hasło</label>
          <input class="form-control" type="password" name="newpassword" id="newpassword" />
        </div>
        <div id="passwordalert" class="alert alert-danger collapse" role="alert">
        </div>
        <input class="submit btn btn-primary" type="submit" value="Zmień hasło" />
      </form>
    </main>
  </body>
</html>

