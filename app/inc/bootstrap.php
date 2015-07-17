<?php

$connstring = "dbname=" . $config['db']['name']
. " host=" . $config['db']['host']
. " user=" . $config['db']['user']
. " password=" . $config['db']['password'];

// The persistent database connection
$DB = pg_pconnect($connstring);
if(!$DB) die("Error: Can't connect to the database.");
