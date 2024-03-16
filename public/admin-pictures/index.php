<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>Użytkownicy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require __DIR__.'/../../utility/head.php'; ?>
    <script src="script.js"></script>
  </head>
  <body class="bg-dark">
    <?php $noLogin = true; require __DIR__.'/../../utility/nav.php'; ?>
    <main class="main bg-light container">
      <header><h2>Zdjęcia</h2></header>
      <table class="table table-hover align-middle" id="posts">
        <thead>
          <tr>
            <th>Id</th>
            <th>Tytuł</th>
            <th>Opis</th>
            <th>Autor</th>
            <th>Liczba zdjęć</th>
            <th class="col-1">Usuń</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <template id="postrow">
        <tr>
          <td>@@id@@</td>
          <td>@@title@@</td>
          <td>@@description@@</td>
          <td>@@uploader@@</td>
          <td>@@imagecount@@</td>
          <td><button class="btn btn-danger py-1" onclick="delete_post(this)"><i class="bi bi-trash-fill"></i></button></td>
        </tr>
      </template>
    </main>
  </body>
</html>

