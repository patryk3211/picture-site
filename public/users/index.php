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
      <header><h2>Użytkownicy</h2></header>
      <table class="table table-hover" id="users">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nazwa użytkownika</th>
            <th>Musi zmienić hasło</th>
            <th>Zarządza użytkownikami</th>
            <th>Dodaje zdjęcia</th>
            <th>Usuwa zdjęcia</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="alert alert-danger collapse" id="permissionalert">
        Nie posiadasz uprawnień do tej strony
      </div>
      <div class="alert alert-success collapse" id="usercreated">
        Użytkownik został utworzony pomyślnie
      </div>
      <div class="container">
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createusermodal"><i class="bi bi-person-fill-add"></i> Stwórz użytkownika</button>
      </div>
      <template id="userrow">
        <tr>
          <td>@@id@@</td>
          <td>@@username@@</td>
          <td>@@changepassword@@</td>
          <td>@@manageusers@@</td>
          <td>@@addpictures@@</td>
          <td>@@deletepictures@@</td>
        </tr>
      </template>
    </main>
    <div class="modal fade" id="createusermodal" data-bs-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">Tworzenie użytkownika</h2>
          </div>
          <div class="modal-body">
            <form class="form">
              <h5>Dane logowania</h5>
              <div class="input-group mb-3">
                <span class="input-group-text" id="user-addon"><i class="bi bi-person-fill"></i></span>
                <input class="form-control" type="text" name="username" id="username" placeholder="Nazwa użytkownika" aria-describedby="user-addon" />
              </div>
              <div class="input-group mb-2">
                <span class="input-group-text" id="password-addon"><i class="bi bi-key-fill"></i></span>
                <input class="form-control" type="password" name="password" id="password" placeholder="Hasło" aria-describedby="password-addon" />
              </div>
              <div class="form-group mb-2">
                <input class="form-check-input ms-1" type="checkbox" id="check-change-password" name="changepassword" />
                <label class="form-check-label ms-1" for="check-change-password">Zmiana hasła po logowaniu</label>
              </div>
              <div class="form-group">
                <h5>Uprawnienia</h5>
                <div class="form-group">
                  <input class="form-check-input ms-1" type="checkbox" id="check-manage-users" name="manageusers" />
                  <label class="form-check-label ms-1" for="check-manage-users">Zarządzanie użytkownikami</label>
                </div>
                <div class="form-group">
                  <input class="form-check-input ms-1" type="checkbox" id="check-add-pictures" name="addpictures" />
                  <label class="form-check-label ms-1" for="check-add-pictures">Dodawanie zdjęć</label>
                </div>
                <div class="form-group">
                  <input class="form-check-input ms-1" type="checkbox" id="check-delete-pictures" name="deletepictures" />
                  <label class="form-check-label ms-1" for="check-delete-pictures">Usuwanie zdjęć</label>
                </div>
              </div>
            </form>
            <div class="alert alert-danger collapse mb-0 mt-2" id="createuseralert" role="alert">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary create">Stwórz</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button> 
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" data-bs-backdrop="static" id="editusermodal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">Edytowanie użytkownika</h2>
          </div>
          <div class="modal-body">
            <h5>Logowanie</h5>
            <div class="input-group mb-2">
              <span class="input-group-text" id="edit-password-addon"><i class="bi bi-key-fill"></i></span>
              <input class="form-control" type="password" name="password" id="edit-password" placeholder="Hasło" aria-describedby="edit-password-addon" />
            </div>
            <div class="form-group mb-2 clearfix">
              <input class="align-middle form-check-input ms-1 mt-0" type="checkbox" id="edit-check-change-password" name="changepassword" />
              <label class="align-middle form-check-label ms-1" for="edit-check-change-password">Zmiana hasła po logowaniu</label>
              <button class="align-middle btn btn-primary float-end">Zmień hasło</button>
            </div>
          </div>
          <div class="modal-body border-top">
            <h5>Uprawnienia</h5>
            <div class="form-group">
              <input class="form-check-input ms-1" type="checkbox" id="check-manage-users" name="manageusers" />
              <label class="form-check-label ms-1" for="check-manage-users">Zarządzanie użytkownikami</label>
            </div>
            <div class="form-group">
              <input class="form-check-input ms-1" type="checkbox" id="check-add-pictures" name="addpictures" />
              <label class="form-check-label ms-1" for="check-add-pictures">Dodawanie zdjęć</label>
            </div>
            <div class="form-group">
              <input class="form-check-input ms-1" type="checkbox" id="check-delete-pictures" name="deletepictures" />
              <label class="form-check-label ms-1" for="check-delete-pictures">Usuwanie zdjęć</label>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

