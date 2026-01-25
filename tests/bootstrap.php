<?php

//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//
declare(strict_types=1);

require_once __DIR__ . '/../../../tests/bootstrap.php';

\OC_App::loadApp(OCA\Bookshelfs\AppInfo\Application::APP_ID);
OC_Hook::clear();
