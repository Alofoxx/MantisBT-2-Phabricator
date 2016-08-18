<?php
//mantis bug tracker database conection info - this tool only reads.
$host    = '127.0.0.1'; //change if not localhost
$db      = 'mantisbt'; //changeme
$charset = 'utf8';

$M2P_CONFIG = array(
    'mbt_db_dsn' => "mysql:host=$host;dbname=$db;charset=$charset",
    'mbt_db_username' => 'mantisbt', //changeme
    'mbt_db_password' => 'mantisbt', //changeme
    'mbt_db_opt' => array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ),
    //set the next line the base url of your mantis install if you want
    //  links to the mantis system to be made in the imported tickets
    //end it with trailing slash. - Set to '' to disable links.
    'mbt_linkback_url' => 'https://mymantisurl.com/any/subfolders/', //changeme
);
