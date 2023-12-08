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
		
		// Start of the application, calls the main template where Vue.js is getting rendered
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		
		
		
		// Saves ride to json file 
		['name' => 'rides_api#setridedetails', 'url' => '/api/0.1/rides', 'verb' => 'POST'],
		// Listing out all rides from files
		['name' => 'rides_api#getrides', 'url' => '/api/0.1/get', 'verb' => 'GET'],
		// Editing existing ride file
		['name' => 'rides_api#editride', 'url' => '/api/0.1/edit', 'verb' => 'POST'],
		// Deleting selected ride
		['name' => 'rides_api#deleteride', 'url' => '/api/0.1/delete', 'verb' => 'POST'],
		// Save user settings into json
		['name' => 'rides_api#createusersettings', 'url' => '/api/0.1/settings', 'verb' => 'POST'],
		// Listing out user settings 
		['name' => 'rides_api#getusersettings', 'url' => '/api/0.1/getusersettings', 'verb' => 'GET'],
		// Saving user api settings 
		['name' => 'rides_api#saveuserapisettings', 'url' => '/api/0.1/savesettings', 'verb' => 'POST'],
		// Saving bessermitfahren.de login details
		['name' => 'rides_api#savebmfsettings', 'url' => '/api/0.1/savebmfsettings', 'verb' => 'POST'],
		// Saving r2g login details
		['name' => 'rides_api#saver2gsettings', 'url' => '/api/0.1/saver2gsettings', 'verb' => 'POST'],

		// Web Scraping bessermitfahren 
		['name' => 'webscrape#loginbessermitfahren', 'url' => '/loginbessermitfahren', 'verb' => 'GET'],
		// Registering ride on bessermitfahren
		['name' => 'webscrape#registerridebmf', 'url' => '/registerbessermitfahren', 'verb' => 'POST'],

		
		
		
		// Scraping Ride2Go 
		['name' => 'webscrape#loginr2g', 'url' => '/loginride2go', 'verb' => 'GET'],
		// registering rides to r2g
		['name' => 'webscrape#registerr2g', 'url' => '/registerr2g', 'verb' => 'POST'],


		
		]
];
