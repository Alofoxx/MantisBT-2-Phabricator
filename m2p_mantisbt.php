<?php
//core mantisbt functions
function dbpdo(){
  $pdo = new PDO( $M2P_CONFIG['mbt_db_dsn'],
                  $M2P_CONFIG['mbt_db_username'],
                  $M2P_CONFIG['mbt_db_password'],
                  $M2P_CONFIG['mbt_db_opt']
                );
  return $pdo;
}

function mbt_getProjects(){
  //
  $pdo = dbpdo();
  $mantis_project_table = array();

  $results = $pdo->query('SELECT * FROM `mantis_project_table`');
  foreach ($results as $row)
  {
      $mantis_project_table[$row['id']]['id'] = $row['id'];
      $mantis_project_table[$row['id']]['name'] = $row['name'];
      $mantis_project_table[$row['id']]['description'] = $row['description'];
      $mantis_project_table[$row['id']]['enabled'] = $row['enabled'];
  }

  $_SESSION['mbt']['mantis_project_table'] = $mantis_project_table;
  return;
}

function mbt_getUsers(){
  //
  $pdo = dbpdo();
  $mantis_user_table = array();

  $results = $pdo->query('SELECT * FROM `mantis_user_table`');
  foreach ($results as $row)
  {
      $mantis_user_table[$row['id']]['id'] = $row['id'];
      $mantis_user_table[$row['id']]['username'] = $row['username'];
      $mantis_user_table[$row['id']]['realname'] = $row['realname'];
      $mantis_user_table[$row['id']]['email'] = $row['email'];
      $mantis_user_table[$row['id']]['enabled'] = $row['enabled'];
      $mantis_user_table[$row['id']]['access_level'] = $row['access_level'];
  }

  $_SESSION['mbt']['mantis_user_table'] = $mantis_user_table;
  return;
}

function mbt_getTags(){
  //
  $pdo = dbpdo();
  $mantis_tag_table = array();

  $results = $pdo->query('SELECT * FROM `mantis_tag_table`');
  foreach ($results as $row)
  {
      $mantis_tag_table[$row['id']]['id'] = $row['id'];
      $mantis_tag_table[$row['id']]['name'] = $row['name'];
      $mantis_tag_table[$row['id']]['description'] = $row['description'];
  }

  $_SESSION['mbt']['mantis_tag_table'] = $mantis_tag_table;
  return;
}

function mbt_getVersions(){
  //
  $pdo = dbpdo();
  $data = array();

  $results = $pdo->query('SELECT * FROM `mantis_project_version_table`');
  foreach ($results as $row)
  {
      $data[$row['project_id']][$row['id']]['id'] = $row['id'];
      $data[$row['project_id']][$row['id']]['project_id'] = $row['project_id'];
      $data[$row['project_id']][$row['id']]['version'] = $row['version'];
      $data[$row['project_id']][$row['id']]['description'] = $row['description'];
      $data[$row['project_id']][$row['id']]['released'] = $row['released'];
  }

  $_SESSION['mbt']['mantis_project_version_table'] = $data;
  return;
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
