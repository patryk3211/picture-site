async function create_user() {
  var form = document.querySelector('#createusermodal .form');
  var json = {
    username: form.username.value,
    password: form.password.value,
    changepassword: form.changepassword.checked,
    manageusers: form.manageusers.checked,
    addpictures: form.addpictures.checked,
    deletepictures: form.deletepictures.checked
  };

  var response = await api_call_with_session("/user/create", json);
  if(response.status == 200) {
    var json = JSON.parse(await response.text());
    if(!json.result && json.message !== undefined) {
      var obj = bootstrap.Collapse.getOrCreateInstance('#createuseralert');
      obj._element.textContent = json.message;
      obj.show();
    } else {
      var obj = bootstrap.Collapse.getOrCreateInstance('#usercreated');
      obj.show();
      bootstrap.Modal.getOrCreateInstance('#createusermodal').hide();
      var lastRow = document.querySelector('#users tbody tr:last-child');

      var newRow = document.createElement('tr');
      newRow.innerHTML = 
`<td>?</td>
<td>${form.username.value}</td>
<td>${form.changepassword.checked ? 1 : 0}</td>
<td>${form.manageusers.checked ? 1 : 0}</td>
<td>${form.addpictures.checked ? 1 : 0}</td>
<td>${form.deletepictures.checked ? 1 : 0}</td>
`;
      lastRow.after(newRow);
      document.querySelector('#createusermodal form').reset();

      setTimeout(() => obj.hide(), 3000);
    }
  } else {
    var obj = bootstrap.Collapse.getOrCreateInstance('#createuseralert');
    obj._element.textContent = "Błąd serwera, status: " + response.status;
    obj.show();
  }
}

async function change_password() {
  var password = document.querySelector('#edit-password').value;
  var changeAfterLogin = document.querySelector('#edit-check-change-password').checked;
  var userid = parseInt(document.querySelector('#edit-user-id').value);
  if(isNaN(userid))
    return;

  var response = await api_call_with_session('/user/setpass', { password: password, changepassword: changeAfterLogin, userid: userid });
  if(response.status != 200) {
    var obj = bootstrap.Collapse.getOrCreateInstance('#editalert')
    obj._element.textContent = 'Błąd serwera, status: ' + response.status;
    obj.show();
    setTimeout(() => obj.hide(), 2000);
  } else {
    var obj = bootstrap.Collapse.getOrCreateInstance('#editsuccess');
    obj._element.textContent = 'Hasło zmienione';
    obj.show();
    setTimeout(() => obj.hide(), 2000);
  }
}

async function change_permissions() {
  var manageusers = document.querySelector('#edit-check-manage-users').checked;
  var addpictures = document.querySelector('#edit-check-add-pictures').checked;
  var deletepictures = document.querySelector('#edit-check-delete-pictures').checked;
  var userid = parseInt(document.querySelector('#edit-user-id').value);
  if(isNaN(userid))
    return;

  var response = await api_call_with_session('/user/setperm', { manageusers: manageusers, addpictures: addpictures, deletepictures: deletepictures, userid: userid });
  if(response.status != 200) {
    var obj = bootstrap.Collapse.getOrCreateInstance('#editalert')
    obj._element.textContent = 'Błąd serwera, status: ' + response.status;
    obj.show();
    setTimeout(() => obj.hide(), 2000);
  } else {
    var obj = bootstrap.Collapse.getOrCreateInstance('#editsuccess');
    obj._element.textContent = 'Uprawnienia zmienione';
    obj.show();
    setTimeout(() => obj.hide(), 2000);
  }
}

document.addEventListener('DOMContentLoaded', async () => {
  var response = await api_call_with_session('/users');
  if(response.status == 401) {
    var obj = bootstrap.Collapse.getOrCreateInstance('#permissionalert');
    obj.show();
  } else if(response.status == 200) {
    var json = JSON.parse(await response.text());
    fill_table(json, document.querySelector('#users'), document.querySelector('#userrow'), row => {
      row.addEventListener('click', event => {
        var row = event.target;
        if(event.target.nodeName == 'TD')
          row = event.target.parentElement;
        document.querySelector('#edit-user-id').value = row.children[0].textContent;
        var modal = bootstrap.Modal.getOrCreateInstance('#editusermodal');
        modal.show();
        // console.log(row);
      });
    });
  }

  var usercreate = document.querySelector('#createusermodal');
  usercreate.querySelector('.create').addEventListener('click', create_user);

  document.querySelector('#edit-pass-change').addEventListener('click', change_password);
  document.querySelector('#edit-perm-change').addEventListener('click', change_permissions);
});

