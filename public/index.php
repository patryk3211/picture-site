<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>Zdjęcia</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $validateSession = false; require __DIR__.'/../utility/head.php'; ?>
    <script src="js/picture_fetch.js"></script>
    <link href="css/gallery.css" rel="stylesheet">
  </head>
  <body class="bg-dark">
    <?php $noLogin = false; require __DIR__.'/../utility/nav.php'; ?>
    <main class="main bg-light container" id="main">
      <header><h1 class="text-center mb-3">Zobacz najnowsze zdjęcia</h1></header>
      <div id="spinner" class="spinner-border mx-auto d-block text-secondary" role="status">
        <span class="visually-hidden">Ładowanie...</span>
      </div>
      <nav id="pages">
        <ul class="pagination">
          <li class="page-item ms-auto"><a class="page-link" id="pageprev">&laquo;</a></li>
          <li class="page-item me-auto"><a class="page-link" id="pagenext">&raquo;</a></li>
        </ul>
      </nav>
    </main>
    <template id="articletemplate">
      <article class="mb-3">
        <div class="clearfix">
          <header class="d-inline-block"><h2 class="title mb-0 d-inline-block w-auto"></h2></header>
          <small class="d-inline-block align-bottom date ms-2"></small>
        </div>
        <hr class="my-1" />
        <div class="description mb-2">
        </div>
        <div class="gallery images d-flex flex-wrap gap-3" >
        </div>
      </article>
    </template>
    <div class="modal modal-xl fade" id="picturemodal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-body w-75">
          <img id="picturemodal-img" class="w-100 shadow-lg" />
        </div>
      </div>
    </div>
  </body>
</html>

