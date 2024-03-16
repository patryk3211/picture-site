var imageIndex = 0;
var images = [];

function delete_image(event) {
  var index = event.target.imgIndex;
  delete images[index];
  event.target.parentElement.parentElement.remove();
}

async function change_preview(event) {
  var files = event.target.files;
  var imagePreview = document.querySelector('#imagepreview');

  var spinner = document.createElement('div');
  spinner.classList.add('spinner-border');
  spinner.classList.add('text-secondary');
  spinner.role = 'status';
  spinner.innerHTML = `<span class="visually-hidden">Processing...</span>`;
  imagePreview.appendChild(spinner);

  for(var i = 0; i < files.length; ++i) {
    var file = files[i];
    var stream = file.stream();

    var binaryString = '';
    for await(const chunk of stream) {
      binaryString += Array.from(chunk, (byte) => String.fromCodePoint(byte)).join('');
    }
    var base64 = 'data:' + file.type + ';base64,' + btoa(binaryString);
    images[imageIndex] = base64;

    var container = document.createElement('div');
    container.classList.add('image-preview');
    container.classList.add('position-relative');
    container.classList.add('w-auto');
    container.innerHTML = 
`<img src=${base64} alt="${file.name}" height="200" class="rounded" />
<div class="overlay position-absolute top-0 start-0 w-100 h-100 text-light rounded" style="background-color: #0005">
  <i class="delete position-absolute bi bi-trash-fill fs-1 end-0 me-1"></i>
</div>`;
    var delEl = container.querySelector('.delete');
    delEl.imgIndex = imageIndex;
    delEl.addEventListener('click', delete_image);
    spinner.before(container);
    ++imageIndex;
  }

  spinner.remove();
}

var imagesSent = false;
async function send_images() {
  var title = document.querySelector('#uploadform').title.value;
  var obj = bootstrap.Collapse.getInstance('#uploadalert');
  if(obj == null) obj = new bootstrap.Collapse('#uploadalert', { toggle: false });
  if(title == '') {
    obj._element.textContent = 'Nie podano tytułu';
    obj.show();
    return;
  } else if(images.filter(v => v).length == 0) {
    // No images to upload
    obj._element.textContent = 'Nie dodano żadnych zdjęć';
    obj.show();
    return;
  }
  obj.hide();

  imagesSent = false;
  var info = bootstrap.Collapse.getInstance('#uploadinfo');
  if(info == null) {
    info = new bootstrap.Collapse('#uploadinfo', { toggle: false });
    info._element.addEventListener('shown.bs.collapse', () => {
      if(imagesSent)
        info.hide();
    })
  }
  info.show();

  var imagesJson = [];
  for(var image of images) {
    if(image == undefined)
      continue;
    imagesJson.push(image);
  }

  var description = document.querySelector('#uploadform').description.value;
  var response = await api_call_with_session('/upload', { title: title, description: description, images: imagesJson });
  imagesSent = true;
  info.hide();

  if(response.status == 200) {
    var success = bootstrap.Collapse.getOrCreateInstance('#uploadsuccess');
    success.show();
    setTimeout(() => success.hide(), 3000);
  } else {
    obj._element.textContent = 'Błąd serwera przy przesyłaniu zdjęć, status: ' + response.status;
    obj.show();
  }
}

function clear_images() {
  images = [];
  document.querySelector('#imagepreview').replaceChildren([]);
}

document.addEventListener('DOMContentLoaded', () => {
  var fileSelector = document.querySelector('#uploadform').images;
  fileSelector.addEventListener('change', change_preview);

  document.querySelector('#uploadform .send').addEventListener('click', send_images);
  document.querySelector('#uploadform .clear').addEventListener('click', clear_images);
});
