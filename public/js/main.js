function api_call(endpoint, json, method) {
  var options = {
    method: method !== undefined ? method : 'POST',
  };
  if(json !== undefined && json !== null) {
    options.headers = {
      'Content-Type': 'application/json'
    };
    options.body = JSON.stringify(json);
  }
  return fetch(API_ENDPOINT + endpoint, options);
}

function api_call_with_session(endpoint, json) {
  var session = sessionStorage.getItem('SESSION_ID');
  if(json === undefined)
    json = {};
  json.session = session;
  return api_call(endpoint, json);
}

function logged_in() {
  return sessionStorage.getItem('SESSION_ID') != null;
}

function fill_table(data, table, template, rowCallback) {
  var tbody = table.querySelector('tbody');

  data.forEach(rowData => {
    var row = template.content.cloneNode(true);
    var cells = row.querySelectorAll('td');
    cells.forEach(cell => {
      if(cell.children.length != 0)
        return;
      var result = cell.textContent.replace(/@@(\w+)@@/, function(text, key) {
        var value = rowData[key];
        if(value === undefined)
          value = '';
        return value;
      });
      cell.textContent = result;
    });
    tbody.appendChild(row);
    var inserted = tbody.children[tbody.children.length - 1];
    if(rowCallback !== undefined) rowCallback(inserted);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  var admin = document.querySelector('.navbar .subnav-admin');
  if(admin == null)
    return;
  var login = document.querySelector('.navbar .subnav-login');
  if(logged_in()) {
    login.classList.add('d-none');
    admin.classList.remove('d-none');
  }
});

