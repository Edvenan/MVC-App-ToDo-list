<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */

$routes = array(
	'/'			=> 'ToDo#index',
	'/create'	=> 'ToDo#createTask',
	'/delete'	=> 'ToDo#deleteTask',
	'/search'	=> 'ToDo#searchTask',
	'/showAll'	=> 'ToDo#showAllTasks',
	'/update'   => 'ToDo#updateTask',
	'/showTask' => 'ToDo#showTask'
	
);
