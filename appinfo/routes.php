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
        'rides' => ['url' => 'apps/rides'],
		'rides_api' => ['url' => '/api/0.1/rides']
    ],

	'routes' => [
		
		// basic main page route 
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		
		
		
		
		
		// API routes 
		['name' => 'rides_api#setridedetails', 'url' => '/api/0.1/rides', 'verb' => 'POST'],

		['name' => 'rides_api#getrides', 'url' => '/api/0.1/get', 'verb' => 'GET'],
		['name' => 'rides_api#editride', 'url' => '/api/0.1/edit', 'verb' => 'POST'],
		['name' => 'rides_api#deleteride', 'url' => '/api/0.1/delete', 'verb' => 'POST'],

		]
];
