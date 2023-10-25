<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Daniel Brenyo <brenyodani@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\Rides\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	
	'resources' => [
        'rides' => ['url' => '/rides']
    ],

	$requirements = [
		'apiVersion' => 'v1',
	],

	'ocs' => [

	],


	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET']
	]
];
