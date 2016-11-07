<?php

/**
 * MantisBT exporting functions.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */
function dbpdo()
{
    $pdo = new PDO($GLOBALS['M2P_CONFIG']['mbt_db_dsn'],
        $GLOBALS['M2P_CONFIG']['mbt_db_username'],
        $GLOBALS['M2P_CONFIG']['mbt_db_password'],
        $GLOBALS['M2P_CONFIG']['mbt_db_opt']
    );
    return $pdo;
}

function mbt_getProjects()
{
    //main Project, controlls what projects get imported
    $pdo                  = dbpdo();
    $mantis_project_table = array();

    $results = $pdo->query('SELECT * FROM `mantis_project_table`');
    foreach ($results as $row) {
        $mantis_project_table[$row['id']]['id']          = $row['id'];
        $mantis_project_table[$row['id']]['name']        = $row['name'];
        $mantis_project_table[$row['id']]['description'] = base64_encode($row['description']);
        $mantis_project_table[$row['id']]['enabled']     = $row['enabled'];
    }

    $_SESSION['mbt']['mantis_project_table'] = $mantis_project_table;
    return;
}

function mbt_getUsers()
{
    //users.
    $pdo               = dbpdo();
    $mantis_user_table = array();

    $results = $pdo->query('SELECT * FROM `mantis_user_table`');
    foreach ($results as $row) {
        $mantis_user_table[$row['id']]['id']           = $row['id'];
        $mantis_user_table[$row['id']]['username']     = $row['username'];
        $mantis_user_table[$row['id']]['realname']     = $row['realname'];
        $mantis_user_table[$row['id']]['email']        = $row['email'];
        $mantis_user_table[$row['id']]['enabled']      = $row['enabled'];
        $mantis_user_table[$row['id']]['access_level'] = $row['access_level'];
        //support for Phab user titles
        $mantis_user_table[$row['id']]['title']        = "";
    }

    $_SESSION['mbt']['mantis_user_table'] = $mantis_user_table;
    return;
}

function mbt_getTags()
{
    //these will be just Projects.
    $pdo              = dbpdo();
    $mantis_tag_table = array();

    $results = $pdo->query('SELECT * FROM `mantis_tag_table`');
    foreach ($results as $row) {
        $mantis_tag_table[$row['id']]['id']          = $row['id'];
        $mantis_tag_table[$row['id']]['name']        = $row['name'];
        $mantis_tag_table[$row['id']]['description'] = $row['description'];
    }

    $_SESSION['mbt']['mantis_tag_table'] = $mantis_tag_table;
    return;
}

function mbt_getVersions()
{
    //user can set these as phab project milestones or just projects.
    $pdo  = dbpdo();
    $data = array();

    $results = $pdo->query('SELECT * FROM `mantis_project_version_table`');
    foreach ($results as $row) {
        $data[$row['project_id']][$row['id']]['id']          = $row['id'];
        $data[$row['project_id']][$row['id']]['project_id']  = $row['project_id'];
        $data[$row['project_id']][$row['id']]['version']     = $row['version'];
        $data[$row['project_id']][$row['id']]['description'] = base64_encode($row['description']);
        $data[$row['project_id']][$row['id']]['released']    = $row['released'];
    }

    $_SESSION['mbt']['mantis_project_version_table'] = $data;
    return;
}

function mbt_getCustomFields()
{
    //I dont see a clear way to inforce project specific custom fileds for
    // manifest. so i will treat it as a all or nothing. User can include the field
    // if they want it, but the import will not limit it to a certain project
    $pdo  = dbpdo();
    $data = array();

    $results = $pdo->query('SELECT * FROM `mantis_custom_field_table`');
    foreach ($results as $row) {
        $data[$row['id']]['id']              = $row['id'];
        $data[$row['id']]['name']            = $row['name'];
        $data[$row['id']]['type']            = $row['type'];
        //posible values would only matter if we are making a select in phab
        $data[$row['id']]['possible_values'] = $row['possible_values'];
    }

    $_SESSION['mbt']['mantis_custom_field_table'] = $data;
    return;
}

function mbt_getCustomFieldAnswers()
{
    //this will end up being applied to ticket array.
    $pdo  = dbpdo();
    $data = array();

    //no point in importing blank answers.
    $results = $pdo->query('SELECT * FROM `mantis_custom_field_string_table` WHERE `value` != ""');

    // data['custom field id'] = array( "bug number" => "answer", ... )
    foreach ($results as $row) {
        $data[$row['field_id']][$row['bug_id']] = $row['value'];
    }

    $_SESSION['mbt']['mantis_custom_field_string_table'] = $data;
    return;
}

function mbt_getCategories()
{
    //these will be set to phab constants or be maniphest.custom-field-definitions
    $pdo  = dbpdo();
    $data = array();

    $results = $pdo->query('SELECT * FROM `mantis_category_table`');
    foreach ($results as $row) {
        $data[$row['id']]['id']   = $row['id'];
        $data[$row['id']]['name'] = $row['name'];
    }

    $_SESSION['mbt']['mantis_category_table'] = $data;
    return;
}

function mbt_getTickets()
{
    //aka mantis bugs aka maniphest tasks
    $pdo  = dbpdo();
    $data = array();

    $results = $pdo->query('SELECT mantis_bug_table.*,
                            mantis_bug_text_table.description,
                            mantis_bug_text_table.steps_to_reproduce,
                            mantis_bug_text_table.additional_information
                          FROM `mantis_bug_table`
                          LEFT JOIN mantis_bug_text_table
                          ON mantis_bug_table.id = mantis_bug_text_table.id');
    foreach ($results as $row) {
        $data[$row['project_id']][$row['id']]['id']                     = $row['id'];
        $data[$row['project_id']][$row['id']]['project_id']             = $row['project_id'];
        $data[$row['project_id']][$row['id']]['reporter_id']            = $row['reporter_id'];
        $data[$row['project_id']][$row['id']]['handler_id']             = $row['handler_id'];
        $data[$row['project_id']][$row['id']]['duplicate_id']           = $row['duplicate_id'];
        $data[$row['project_id']][$row['id']]['priority']               = $row['priority'];
        $data[$row['project_id']][$row['id']]['severity']               = $row['severity'];
        $data[$row['project_id']][$row['id']]['reproducibility']        = $row['reproducibility'];
        $data[$row['project_id']][$row['id']]['status']                 = $row['status'];
        $data[$row['project_id']][$row['id']]['resolution']             = $row['resolution'];
        $data[$row['project_id']][$row['id']]['projection']             = $row['projection'];
        $data[$row['project_id']][$row['id']]['eta']                    = $row['eta'];
        $data[$row['project_id']][$row['id']]['bug_text_id']            = $row['bug_text_id'];
        $data[$row['project_id']][$row['id']]['version']                = $row['version'];
        $data[$row['project_id']][$row['id']]['fixed_in_version']       = $row['fixed_in_version'];
        $data[$row['project_id']][$row['id']]['profile_id']             = $row['profile_id'];
        $data[$row['project_id']][$row['id']]['view_state']             = $row['view_state'];
        $data[$row['project_id']][$row['id']]['summary']                = $row['summary'];
        $data[$row['project_id']][$row['id']]['target_version']         = $row['target_version'];
        $data[$row['project_id']][$row['id']]['category_id']            = $row['category_id'];
        $data[$row['project_id']][$row['id']]['date_submitted']         = $row['date_submitted'];
        $data[$row['project_id']][$row['id']]['last_updated']           = $row['last_updated'];
        $data[$row['project_id']][$row['id']]['description']            = base64_encode($row['description']);
        $data[$row['project_id']][$row['id']]['steps_to_reproduce']     = base64_encode($row['steps_to_reproduce']);
        $data[$row['project_id']][$row['id']]['additional_information'] = base64_encode($row['additional_information']);
    }

    $_SESSION['mbt']['mantis_bug_table'] = $data;
    return;
}

function mbt_getComments()
{
    //bugnotes will just be added to tickets as discussion
    //im not planning to implement note_type [like reminders] - unless asked?
//need to update the comment authors in two places once added.
// maniphest_transaction_comment
// maniphest_transaction
    $pdo  = dbpdo();
    $data = array();

    $results = $pdo->query('SELECT mantis_bugnote_table.*,
                            mantis_bugnote_text_table.note
                          FROM mantis_bugnote_table
                          LEFT JOIN mantis_bugnote_text_table
                          ON mantis_bugnote_table.bugnote_text_id = mantis_bugnote_text_table.id');

    // data['bug number'] = array( "bugnote number" => array(note data), ... )
    foreach ($results as $row) {
        $data[$row['bug_id']][$row['id']]['id']             = $row['id'];
        $data[$row['bug_id']][$row['id']]['bug_id']         = $row['bug_id'];
        $data[$row['bug_id']][$row['id']]['reporter_id']    = $row['reporter_id'];
        $data[$row['bug_id']][$row['id']]['last_modified']  = $row['last_modified'];
        $data[$row['bug_id']][$row['id']]['date_submitted'] = $row['date_submitted'];
        $data[$row['bug_id']][$row['id']]['note']           = base64_encode($row['note']);
    }

    $_SESSION['mbt']['mantis_bugnote_table'] = $data;
    return;
}

function mbt_getFiles()
{
    //support file attachments from tickets.
}
