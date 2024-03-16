window.addEventListener('load', async () => {
  // Check for a valid session
  var session = sessionStorage.getItem('SESSION_ID');
  if(session == null) {
    location.replace('/login/');
    return;
  }
  var response = await api_call_with_session('/check');
  if(response.status != 200) {
    sessionStorage.removeItem('SESSION_ID');
    location.replace('/login/');
    return;
  }
});

