<!DOCTYPE html>
<html lang="pl">
  <head>
    <?php $noBootstrap = true; require __DIR__.'/../../utility/head.php'; ?>
    <script>
      window.addEventListener('load', async () => {
        await api_call_with_session('/logout');
        sessionStorage.removeItem('SESSION_ID');
        location.replace('/');
      });
      logout();
    </script>
  </head>
</html>

