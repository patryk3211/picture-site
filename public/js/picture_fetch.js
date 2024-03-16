function big_picture(event) {
  var image = event.target.src;
  document.querySelector('#picturemodal-img').src = image;

  var modal = bootstrap.Modal.getOrCreateInstance('#picturemodal');
  modal.show();

}

document.addEventListener('DOMContentLoaded', async () => {
  var template = document.querySelector('#articletemplate');
  var pages = document.querySelector('#pages');

  var search = new URLSearchParams(location.search);

  var currentPage = 0;
  if(!search.has('offset') || search.get('offset') == 0) {
    document.querySelector('#pageprev').parentElement.classList.add('disabled');
  } else {
    currentPage = Math.floor(search.get('offset') / 10);
    document.querySelector('#pageprev').href = '?offset=' + Math.max((currentPage - 1) * 10, 0);
  }

  var response = await api_call('/post_count', null, 'GET');
  if(response.status == 200) {
    var json = JSON.parse(await response.text());
    var pageCount = Math.ceil(json.count / 10);
    for(var i = 0; i < pageCount; ++i) {
      var liEl = document.createElement('li');
      liEl.classList.add('page-item');
      var aEl = document.createElement('a');
      aEl.classList.add('page-link');
      aEl.href = '?offset=' + (i * 10);
      aEl.textContent = i + 1;
      if(i == currentPage)
        aEl.classList.add('active');
      liEl.appendChild(aEl);
      pages.querySelector('.page-item:last-child').before(liEl);
    }
    if(currentPage == pageCount - 1) {
      document.querySelector('#pagenext').parentElement.classList.add('disabled');
    } else {
      document.querySelector('#pagenext').href = '?offset=' + ((currentPage + 1) * 10);
    }
  }

  var call_args = '';
  if(search.has('offset')) {
    call_args = '?offset=' + search.get('offset');
  }

  var response = await api_call('/pictures' + call_args, null, 'GET');
  if(response.status == 200) {
    var json = JSON.parse(await response.text());
    json.forEach(post => {
      var node = template.content.cloneNode(true);
      node.querySelector('.title').textContent = post.title;
      node.querySelector('.date').textContent = new Date(post.date).toLocaleString();
      var descNode = node.querySelector('.description');
      if(post.description == '') {
        descNode.innerHTML = '<i>Brak opisu</i>';
      } else {
        descNode.textContent = post.description;
      }

      var images = node.querySelector('.images');
      post.images.forEach(image => {
        var imgNode = document.createElement('img');
        imgNode.classList.add('rounded');
        imgNode.src = image;
        imgNode.height = 200;
        imgNode.addEventListener('click', big_picture);

        var attr = document.createAttribute('data-bs-target');
        attr.value = '#picturemodal';
        imgNode.attributes.setNamedItem(attr);
        attr = document.createAttribute('data-bs-toggle');
        attr.value = true;
        imgNode.attributes.setNamedItem(attr);

        images.appendChild(imgNode);
      });

      pages.before(node);
    });

    if(json.length == 0) {
      var node = document.createElement('div');
      node.classList.add('text-center');
      node.classList.add('mb-3');
      node.textContent = 'Nic tutaj nie ma...';
      pages.before(node);
    }

    document.querySelector('#spinner').remove();
  }
});
