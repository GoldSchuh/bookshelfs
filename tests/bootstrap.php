<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../tests/bootstrap.php'; // FIXME Does this need access to nextcloud_docker_dev?

\OC_App::loadApp(OCA\Bookshelfs\AppInfo\Application::APP_ID);
OC_Hook::clear();
