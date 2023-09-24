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
	'/showAll'	=> 'ToDo#showAllTasks',
	'/showTask' => 'ToDo#showTask',
	'/sort'		=> 'ToDo#sortTasks',
	'/update'   => 'ToDo#updateTask',
	'/delete'	=> 'ToDo#deleteTask',
	'/error'	=> 'error#error'
);
