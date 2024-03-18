async function login() {
  var form = document.querySelector('#loginform');
  
  var response = await api_call('/auth', { user: form.username.value, password: form.password.value });
  if(response.status == 200) {
    var json = JSON.parse(await response.text());
    if(!json.result && json.changepassword) {
      sessionStorage.setItem('username', form.username.value);
      location.replace(URL_PREFIX + '/changepassword/');
    } else if(!json.result && json.message != undefined) {
      var obj = bootstrap.Collapse.getOrCreateInstance('#loginalert');
      obj._element.textContent = json.message;
      obj.show();
    } else if(json.result) {
      sessionStorage.setItem('SESSION_ID', json.session);
      location.assign(URL_PREFIX + '/');
    }
  }
}

window.addEventListener('load', () => {
  var submit = document.querySelector('#loginform .submit');
  submit.addEventListener('click', login);

  var username = sessionStorage.getItem('username');
  if(username != null)
    document.querySelector('#loginform').username.value = username;
});
