<?php

declare(strict_types=1);

use OCP\Util;

$appId = OCA\Bookshelfs\AppInfo\Application::APP_ID;
Util::addScript($appId, $appId . '-main');
