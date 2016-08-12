<?php
/**
 * This project was made to provide a usefull tool to help migrate data from
 * a Mantis Bug Tracker database into a Phabricator installation via Conduit
 * (the Phabricator API).
 *
 * I made this for my own org to move to Phabricator so the functionaly is
 * mainly driven by what we needed to transfer. I make no warranty that this
 * code will work in the way you may need it to. Or that it wont blow up your
 * server. Or that its complete. Or that it will be updated. So don't blame me.
 *
 * (should work though!)
 *
 * I'm not against keeping it alive/updated/improving. Just low priority for me.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */
 //error_reporting(E_ALL ^ E_NOTICE);
 error_reporting(E_ALL);
 ini_set("display_errors",true);

// Start the session
session_start();

if(isset($_GET['action']) && $_GET['action'] == "reset" ) {
  //we want to nuke everything in session and start over
  foreach($_SESSION as $key => $value){
				unset($_SESSION[$key]);
  }
}

/**
 * Turns on debugging for dev. Default false.
 */
defined('M2P_DEBUG') or define('M2P_DEBUG', true);

//all the connection settings are in this file:
require(__DIR__ . '/m2p_config.php');

//core files
require(__DIR__ . '/m2p_mantisbt.php');

//do stuff

mbt_getProjects();
mbt_getUsers();
mbt_getTags();
mbt_getVersions();
mbt_getCustomFields();
mbt_getCustomFieldAnswers();
mbt_getCategories();
mbt_getTickets();
mbt_getComments();











/*
most of this is white boarded so only adding text to give viewers a rough
idea of the plan.. very much a WIP.


==MANTIS EXPORTING==
The rough plan:

essentiailly all tickets will be maniphest tasks

tags, projects, categories, will be phab projects

versions will be mostly milestones of main project. otherwise just projects.

custom fields will be custom-field-definitions except for Multiselection lists

Multiselection lists dont exist as a type in maniphest.custom-field-definitions
so i will treat those as projects since we only used it for tracking what
company(s) wanted the ticket - and phab uses projects this way like for wikimedia

-------
Main Flow (rough):

display instructions and warnings

user importing - settings

load in projects
user confirms what projects will be imported

load in all relevent parts of selected projects
display each mantis bt table as options on what it will be converted to in phab


generate a master array dump to handle with conduit.
let user have that array  dump - make any edits

-----------
mantis2phab conversion hitlist:

mantis tags to phab projects (used as tags)
source mantis_tag_table

mantis project versions to phab project milestones if released = 1
asume based on this but ask user
test if order of milestone creation changes the order of milestones
source mantis_project_version_table

mantis projects to phab projects - if enabled = 1
source mantis_project_table

==PHAB IMPORTING==
provide any needed phab modifications such as maniphest.custom-field-definitions
based on imported things

process the array dump from first steps or user provided into conduit.

*/

//debug printing.
if(M2P_DEBUG){
  print "<pre>";
  print "<br /> /*---------( M2P_CONFIG )-----------------------------*/ <br />";
  print_r($M2P_CONFIG);
  print "<br /> /*---------( _SESSION )-----------------------------*/ <br />";
  print_r($_SESSION);
  print "</pre>";
}
