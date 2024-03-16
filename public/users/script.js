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
        var modal = bootstrap.Modal.getOrCreateInstance('#editusermodal');
        modal.show();
        // console.log(row);
      });
    });
  }

  var usercreate = document.querySelector('#createusermodal');
  usercreate.querySelector('.create').addEventListener('click', create_user);
});

