<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Daniel Brenyo <brenyodani@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Rides\Controller;

use OCA\Rides\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\Util;

class FileController extends Controller {


	public function __construct(IRequest $request) {
		parent::__construct(Application::APP_ID, $request);
        



	}







}


?>