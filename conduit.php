<?php
/**
 * Phabricator conduit use functions.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

//root to libphutil folder in installed phab
$root = "/var/www/phab/libphutil";
require_once $root.'/src/__phutil_library_init__.php';
//conduit token
define("API_TOKEN", "api-xxxxxxxxxxxxxxxxxxxxxx");
//url that serves phab
define("PHAB_URL", "http://xxxxxxxxx.com/");

/*
  //test getOrAddProject
  $testProject = Array
  (
  [id] => "1",
  [name] => "Test1",
  [description] => "The Testing Project",
  [enabled] => "1"
  );

  print "<br><pre>";
  print_r(getOrAddProject($testProject));
 */

/**
 * Finds existing project or creates a new one and returns the project array.
 *
 * @param array $project the import project array.
 * @param array $generatedProjects array of previous returns of this function.
 * @return array $project with phid
 */
function getOrAddProject($project, $generatedProjects)
{
    //validate input
    if (!is_array($project)) {
        //cant do anything.
        return array(
            "error" => true,
            "errorMessage" => "Project araay was not passed to getOrAddProject()"
        );
    }

    //setup params for call
    $api_parameters = array(
        'names' => array(
            $project['name'],
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
            //only reliable way to return first array
            //ignore import values and and overwrite with existing db values
            $project['phid']  = $phid['phid'];
            $project['icon']  = $phid['icon'];
            $project['color'] = $phid['color'];
            return $project;
        }
    }

    //no project was found so make it.
    $validIcons = array(
        "project", "tag", "policy", "group", "folder",
        "timeline", "goal", "release", "bugs", "cleanup",
        "umbrella", "communication", "organization",
        "infrastructure", "account", "experimental"
    );
    if (!isset($project['icon']) || !in_array($project['icon'], $validIcons)) {
        if (isset($project['milestone']) && $project['milestone']) {
            $project['icon'] = "release";
        } else {
            $project['icon'] = "project";
        }
    }
    $validColors = array(
        "red", "orange", "yellow", "green", "blue",
        "indigo", "violet", "pink", "grey", "checkered"
    );
    if (!isset($project['color']) || !in_array($project['color'], $validColors)) {
        $project['color'] = "blue";
    }

    $api_parameters = [
        'transactions' => [
            [
                "type" => "name",
                "value" => $project['name'],
            ],
            [
                "type" => "description",
                "value" => base64_decode($project['description']),
            ],
            [
                "type" => "icon",
                "value" => $project['icon'],
            ],
            [
                "type" => "color",
                "value" => $project['color'],
            ],
        ]
    ];

    //if this is a milestone make it one
    if (isset($project['milestone']) && $project['milestone']) {
        //this assumes that the parent will always be in $generatedProjects
        if (!is_array($generatedProjects)) {
            return array(
                "error" => true,
                "errorMessage" => '$generatedProjects array was not provided to '.__FUNCTION__.'.'
            );
        }

        $api_parameters['transactions'][] = [
            "type" => "milestone",
            "value" => $generatedProjects[$project['project_id']]['phid'],
        ];
    }
    //setup for phab's conduit
    $client = new ConduitClient(PHAB_URL);
    $client->setConduitToken(API_TOKEN);
    //do the connection and query
    try {
        $result = $client->callMethodSynchronous('project.edit', $api_parameters);
    } catch (Exception $exc) {
        //url or token are likely invalid - let user know
        $message = $exc->getMessage();
        return array(
            "error" => true,
            "errorMessage" => $message
        );
    }

    //add phid to the array and return it
    $project['phid'] = $result['object']['phid'];
    return $project;
}
