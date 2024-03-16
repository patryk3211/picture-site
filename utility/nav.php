<nav class="navbar navbar-expand-sm navbar-dark bg-primary" id="mainnav">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="/">Strona główna</a></li>
    </ul>
    <?php if(!$noLogin) { ?>
    <ul class="navbar-nav subnav-login">
      <li class="nav-item"><a class="nav-link" href="/login/">Zaloguj</a></li>
    </ul>
    <ul class="navbar-nav d-none subnav-admin">
    <?php } else { ?>
    <ul class="navbar-nav">
    <?php } ?>
      <li class="nav-item"><a class="nav-link" href="/users/">Użytkownicy</a></li>
      <li class="nav-item dropdown">
          <a role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Zdjęcia</a>
          <ul class="dropdown-menu dropdown-menu-dark">
            <li><a href="/admin-pictures/" class="dropdown-item">Edytuj</a></li>
            <li><a href="/upload/" class="dropdown-item">Wyślij</a></li>
          </ul>
      </li>
      <li class="nav-item"><a class="nav-link" href="/logout/">Wyloguj</a></li>
    </ul>
  </div>
  <script>
  document.querySelectorAll('#mainnav a.nav-link, #mainnav a.dropdown-item').forEach(el => {
    if(el.href == (location.origin + location.pathname)) {
      el.classList.add('active');
    }
  });
  </script>
</nav>

