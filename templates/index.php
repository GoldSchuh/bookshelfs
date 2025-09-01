<?php

declare(strict_types=1);

use OCP\Util;

Util::addScript(OCA\Bookshelfs\AppInfo\Application::APP_ID, OCA\Bookshelfs\AppInfo\Application::APP_ID . '-main');
Util::addStyle(OCA\Bookshelfs\AppInfo\Application::APP_ID, OCA\Bookshelfs\AppInfo\Application::APP_ID . '-main');

?>

<div id="bookshelfs"></div>
