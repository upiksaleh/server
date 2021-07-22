<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\AdminRightSubgranting\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'resources' => [
		'authorized_group' => ['url' => '/authorizedgroups']
	],
	'routes' => [
		['name' => 'authorized_group#create', 'url' => '/authorizedgroups', 'verb' => 'POST'],
		['name' => 'authorized_group#saveSettings', 'url' => '/authorizedgroups/saveSettings', 'verb' => 'POST'],
		['name' => 'authorized_group#destroy', 'url' => '/authorizedgroups', 'verb' => 'DELETE']
	]
];
