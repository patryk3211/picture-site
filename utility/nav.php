<?php
$adminClasses = $noLogin ? 'navbar-nav' : 'navbar-nav d-none subnav-admin';
$loginSubnav = !$noLogin ? <<<HTML
<ul class="navbar-nav subnav-login">
  <li class="nav-item"><a class="nav-link" href="$prefix/login/">Zaloguj</a></li>
</ul>
HTML : '';

echo <<<HTML
<nav class="navbar navbar-expand-sm navbar-dark bg-primary" id="mainnav">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="$prefix/">Strona główna</a></li>
    </ul>
    $loginSubnav
    <ul class="$adminClasses">
      <li class="nav-item"><a class="nav-link" href="$prefix/users/">Użytkownicy</a></li>
      <li class="nav-item dropdown">
          <a role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Zdjęcia</a>
          <ul class="dropdown-menu dropdown-menu-dark">
            <li><a href="$prefix/admin-pictures/" class="dropdown-item">Edytuj</a></li>
            <li><a href="$prefix/upload/" class="dropdown-item">Wyślij</a></li>
          </ul>
      </li>
      <li class="nav-item"><a class="nav-link" href="$prefix/logout/">Wyloguj</a></li>
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
HTML;
?>
