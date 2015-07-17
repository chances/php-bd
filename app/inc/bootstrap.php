<?php

$connstring = "dbname=" . $config['db']['name']
. " host=" . $config['db']['host']
. " user=" . $config['db']['user']
. " password=" . $config['db']['password'];

// The persistent database connection
$GLOBALS['DB'] = pg_pconnect($connstring);
if(!$DB) die("Error: Can't connect to the database.");

function getProjects() {
	$query = "SELECT projects.name, description, expires, repository_types.name as repository_type
              FROM projects
              JOIN repository_types
              ON (projects.repository_type = repository_types.id)";
	$result = pg_query($GLOBALS['DB'], $query);
	return pg_fetch_all($result);
}

function getProjectById($id) {
	$query = "SELECT * FROM projects WHERE id = $1";
	$result = pg_query_params($GLOBALS['DB'], $query, array($id));
	return pg_fetch_all($result);
}

function deleteProjectById($id) {
	$query = "DELETE FROM projects WHERE id = $1";
	$result = pg_query_params($GLOBALS['DB'], $query, array($id));
	return pg_fetch_all($result);
}
