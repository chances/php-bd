<?php

$connstring = "dbname=" . $config['db']['name']
. " host=" . $config['db']['host']
. " user=" . $config['db']['user']
. " password=" . $config['db']['password'];

//$GLOBALS['BASE_URL'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['CONTEXT_PREFIX'];
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
	$protocol = 'https://';
} else $protocol = 'http://';
$GLOBALS['BASE_URL'] = $protocol . $_SERVER['HTTP_HOST'];

// The persistent database connection
$GLOBALS['DB'] = pg_pconnect($connstring);
if(!$DB) die("Error: Can't connect to the database.");

function getProjects() {
	$query = "SELECT projects.id, projects.name, description, expires, repository_types.name as repository_type
              FROM projects
              JOIN repository_types
              ON (projects.repository_type = repository_types.id)";
	$result = pg_query($GLOBALS['DB'], $query);
	if ($result == false) {
		throw new Exception("Could not retrieve projects.");
	}
	$result = pg_fetch_all($result);
	if (count($result) == 0) {
		throw new Exception("There are no projects.", 1);
	}
	return $result;
}

function getProjectById($id) {
	$query = "SELECT projects.id, projects.name, description, expires, repository_types.name as repository_type
              FROM projects
              JOIN repository_types
              ON (projects.repository_type = repository_types.id)
              WHERE projects.id = $1";
	$result = pg_query_params($GLOBALS['DB'], $query, array($id));
	return pg_fetch_array($result);
}

function getRepositoryTypes() {
	$query = "SELECT * from repository_types";
	$result = pg_query($GLOBALS['DB'], $query);
	return pg_fetch_all($result);
}

function newProject($name, $description, $expires, $repository_type = NULL) {
	$query = "INSERT INTO projects (name, description, expires, repository_type) VALUES ( $1, $2, $3, $4)";
	$result = pg_query_params($GLOBALS['DB'], $query, array(
		$name,
		$description,
		$expires,
		$repository_type
	));
	return pg_fetch_all($result);
}

function deleteProjectById($id) {
	$query = "DELETE FROM projects WHERE id = $1";
	$result = pg_query_params($GLOBALS['DB'], $query, array($id));
	return pg_fetch_all($result);
}
