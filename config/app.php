<?php

// Adres dostępu do aplikacji, używany do generowania adresu dostępu do API
define('APP_URL', 'http://127.0.0.1:8080');
// Przedrostek (np. /zdjecia) dodawany do lokalizacji w adresie url: http://127.0.0.1[PREFIX]/[Dalszy ciąg adresu]
define('URL_LOCATION_PREFIX', '');
// Lokalizacja folderu do przechowywania zdjęć w systemie plików.
define('PICTURE_STORAGE_LOCATION', PROJECT_ROOT_PATH.'public/pictures');
// Lokalizacja dostępu do zdjęć przez adres url.
define('PICUTRE_URL_LOCATION', URL_LOCATION_PREFIX.'/pictures');

