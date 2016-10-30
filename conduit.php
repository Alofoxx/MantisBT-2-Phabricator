<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//root to libphutil folder in installed phab
$root = "/var/www/phab/libphutil";
require_once $root.'/src/__phutil_library_init__.php';
//conduit token
define("API_TOKEN", "api-xxxxxxxxxxxxxxxxxxxxxx");
//url that serves phab
define("PHAB_URL", "http://xxxxxxxxx.com/");

/*
 //this tests the api for an existing project name
  $api_parameters = array(
  'names' => array(
  'Bugs',
  ),
  );
  print "<pre>";
  $client = new ConduitClient(PHAB_URL);
  $client->setConduitToken(API_TOKEN);
  try {
  $result = $client->callMethodSynchronous('project.query', $api_parameters);
  } catch (Exception $exc) {
  print $exc->getMessage();
  die;
  }


  print_r($result);
  foreach($result['data'] as $project){
  print $project['phid'];
  }

  print $result['data'][1]['phid'] . "<br>";
  if ($result['data']) {
  print "TRUE";
  } else {
  print "FALSE";
  }


 */
//test
print "<br><pre>";
print_r(getOrAddProject("Test2"));

function getOrAddProject($name)
{
    //validate input
    $name = trim($name);
    if ($name == "") {
        //cant do anything.
        return array(
            "error" => true,
            "errorMessage" => "Project name was not passed to getOrAddProject()"
        );
    }

    //setup params for call
    $api_parameters = array(
        'names' => array(
            $name,
        ),
    );

    //setup for phab's conduit
    $client = new ConduitClient(PHAB_URL);
    $client->setConduitToken(API_TOKEN);

    //do the connection and query
    try {
        $result = $client->callMethodSynchronous('project.query',
            $api_parameters);
    } catch (Exception $exc) {
        //url or token are likely invalid - let user know
        $message = $exc->getMessage();
        return array(
            "error" => true,
            "errorMessage" => $message
        );
    }

    if ($result['data']) {
        //return our phab project details if project name was found.
        foreach ($result['data'] as $phid) {
            //only reliable way to return first one
            $project['error'] = false;
            $project['phid']  = $phid['phid'];
            $project['icon']  = $phid['icon'];
            $project['color'] = $phid['color'];
            return $project;
        }
    }

    //no project was found so make it.

    $api_parameters = [
         ["name" => "Test2"],
        "icon" => "project",
        "color" => "blue",
        /*
        [
            "type" => "name",
            "value" => "Test2",
        ],
        [
            "type" => "icon",
            "value" => "project",
        ],
        [
            "type" => "color",
            "value" => "blue",
        ],*/
    ];

    //setup for phab's conduit
    $client = new ConduitClient(PHAB_URL);
    $client->setConduitToken(API_TOKEN);
    //do the connection and query
    try {
        $result = $client->callMethodSynchronous('project.edit',
            $api_parameters);
    } catch (Exception $exc) {
        //url or token are likely invalid - let user know
        $message = $exc->getMessage();
        return array(
            "error" => true,
            "errorMessage" => $message
        );
    }

    return $result;
}
