<?php

//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//
declare(strict_types=1);

use OCP\Util;

$appId = OCA\Bookshelfs\AppInfo\Application::APP_ID;
Util::addScript($appId, $appId . '-main');
