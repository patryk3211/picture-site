async function delete_post(button) {
  var row = button.parentElement.parentElement;
  var id = parseInt(row.querySelector('td').textContent);
  if(isNaN(id))
    return;

  var response = await api_call_with_session('/pictures/delete', { groupid: id });
  if(response.status == 200) {
    row.remove();
  }
}

document.addEventListener('DOMContentLoaded', async () => {
  var response = await api_call_with_session('/pictures/all');
  if(response.status == 200) {
    var json = JSON.parse(await response.text());
    fill_table(json, document.querySelector('#posts'), document.querySelector('#postrow'));
  }
});
