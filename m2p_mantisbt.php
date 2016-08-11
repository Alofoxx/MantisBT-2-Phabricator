<?php
//core mantisbt functions
$pdo = new PDO( $M2P_CONFIG['mbt_db_dsn'],
                $M2P_CONFIG['mbt_db_username'],
                $M2P_CONFIG['mbt_db_password'],
                $M2P_CONFIG['mbt_db_opt']
              );

$stmt = $pdo->query('SELECT name FROM users');
foreach ($stmt as $row)
{
    echo $row['name'] . "\n";
}

function mbt_getProjects(){
  //
}

function mbt_getUsers(){
  //
}

function mbt_getTags(){
  //
}

function mbt_getVersions(){
  //
}

function mbt_getCustomFields(){
  //
}

function mbt_getCustomFieldAnswers(){
  //
}

function mbt_getCategories(){
  //
}

function mbt_getTickets(){
  //
}

function mbt_getComments(){
  //
}
