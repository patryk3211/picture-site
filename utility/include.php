<?php
require __DIR__.'/config.php';

ini_set('session.use_cookies', 0);

require PROJECT_ROOT_PATH.'database/base.php';
require PROJECT_ROOT_PATH.'database/user.php';

require PROJECT_ROOT_PATH.'utility/response.php';

require PROJECT_ROOT_PATH.'controller/admin_api.php';
require PROJECT_ROOT_PATH.'controller/public_api.php';

