async function changePassword() {
  var form = document.querySelector('#passwordform');
  
  var response = await api_call('/changepass', { user: form.username.value, password: form.oldpassword.value, newpassword: form.newpassword.value });
  if(response.status == 200) {
    var json = JSON.parse(await response.text());
    if(!json.result && json.message != undefined) {
      var obj = bootstrap.Collapse.getOrCreateInstance('#passwordalert');
      obj._element.textContent = json.message;
      obj.show();
    } else if(json.result) {
      location.assign(URL_PREFIX + '/login/');
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  var submit = document.querySelector('#passwordform .submit');
  submit.addEventListener('click', changePassword);

  var username = sessionStorage.getItem('username');
  if(username != null)
    document.querySelector('#passwordform').username.value = username;
});
