<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>Prześlij zdjęcia</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require __DIR__.'/../../utility/head.php'; ?>
    <script src="script.js"></script>
  </head>
  <body class="bg-dark">
    <?php $noLogin = false; require __DIR__.'/../../utility/nav.php' ?>
    <main class="main bg-light container">
      <header><h2>Przesyłanie zdjęć</h2></header>
      <form id="uploadform" onsubmit="event.preventDefault()" class="mb-3">
        <div class="form-group mb-2">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-fonts"></i></span>
            <input class="form-control" type="text" name="title" id="title" placeholder="Tytuł" maxlength="255" />
          </div>
        </div>
        <div class="form-group mb-2">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <textarea class="form-control" multiline placeholder="Opis" maxlength="4096" rows="5" name="description"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="container shadow p-3">
            <h4><i class="bi bi-images"></i> Zdjęcia</h4>
            <div class="container d-flex flex-wrap mb-3 gap-2" id="imagepreview">
            </div>
            <div class="input-group">
              <input class="form-control" type="file" name="images" multiple />
              <button class="btn btn-success send">
                <i class="bi bi-cloud-arrow-up-fill"></i> Wyślij
              </button>
              <button class="btn btn-danger clear">
                <i class="bi bi-trash-fill"></i> Usuń wszystkie
              </button>
            </div>
          </div>
        </div>
      </form>
      <div class="alert alert-danger collapse" id="uploadalert"></div>
      <div class="alert alert-info collapse" id="uploadinfo">
        <div class="spinner-border align-middle">
          <span class="visually-hidden">Przesyłanie...</span>
        </div>
        <span class="align-middle ms-1">Przesyłanie zdjęć</span>
      </div>
      <div class="alert alert-success collapse" id="uploadsuccess">Zdjęcia przesłane</div>
    </main>
  </body>
</html>

