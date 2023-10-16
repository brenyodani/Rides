<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Daniel Brenyo <brenyodani@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Rides\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
	public const APP_ID = 'rides';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}
}
