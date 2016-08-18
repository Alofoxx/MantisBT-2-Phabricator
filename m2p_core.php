<?php

/**
 * Main functions for program logic.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */
function loadSteps()
{
    $_SESSION['wizardStep'] = array(
        "currentStep" => 0,
        "nextStep" => 1,
        "previousStep" => 0,
        "steps" => array(
            "0" => "startPage",
            "1" => "btUser",
            "2" => "btProject",
            "3" => "btVersion",
            "4" => "btTag",
            "5" => "btCategory",
            "6" => "btCustomField",
            "7" => "btBugs",
            "8" => "btComments",
            "9" => "btFiles",
            "10" => "btFinish"
        ),
        "done" => array(
            "0" => false,
            "1" => false,
            "2" => false,
            "3" => false,
            "4" => false,
            "5" => false,
            "6" => false,
            "7" => false,
            "8" => false,
            "9" => false,
            "10" => false
        )
    );
    return;
}

function setPreviousStep($step)
{
    $_SESSION['wizardStep']['previousStep'] = $step;
    return;
}

function setNextStep($step)
{
    $_SESSION['wizardStep']['nextStep'] = $step;
    return;
}

function setCurrentStep($step)
{
    $_SESSION['wizardStep']['currentStep'] = $step;
    return;
}

function runCurrentStep()
{
    //Okay we are running the current step.
    switch ($_SESSION['wizardStep']['currentStep']) {
        case 0:
            loadstartPage();
            break;
        case 1:
            loadbtUser();
            break;
        case 2:
            loadbtProject();
            break;
        case 3:
            loadbtVersion();
            break;
        case 4:
            loadbtTag();
            break;
        case 5:
            loadbtCategory();
            break;
        case 6:
            loadbtCustomField();
            break;
        case 7:
            loadbtBugs();
            break;
        case 8:
            loadbtComments();
            break;
        case 9:
            loadbtFiles();
            break;
        case 10:
            loadbtFinish();
            break;
        default:
            loadStartPage();
            break;
    }
    return;
}

function validateCurrentStep()
{
    //validate post
    switch ($_SESSION['wizardStep']['currentStep']) {
        case 0:
            checkstartPage();
            break;
        case 1:
            checkbtUser();
            break;
        case 2:
            checkbtProject();
            break;
        case 3:
            checkbtVersion();
            break;
        case 4:
            checkbtTag();
            break;
        case 5:
            checkbtCategory();
            break;
        case 6:
            checkbtCustomField();
            break;
        case 7:
            checkbtBugs();
            break;
        case 8:
            checkbtComments();
            break;
        case 9:
            checkbtFiles();
            break;
        case 10:
            checkbtFinish();
            break;
        default:
            //
            break;
    }
    return;
}

function loadstartPage()
{
    setPreviousStep(0);
    setNextStep(1);
    drawTop("Welcome");
    //drawProgress("");
    require(__DIR__.'/template/startPage.html');
    drawBottom();
    return;
}

function checkstartPage()
{
    //checks post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Get Started") {
        setCurrentStep(1);
    }
    return;
}

function loadbtUser()
{
    setPreviousStep(0);
    setNextStep(2);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['users'])) {
        //move exported user into the import array
        mbt_getUsers();
        $_SESSION['phab']['users'] = $_SESSION['mbt']['mantis_user_table'];
    }

    drawProgress("Users");
    require(__DIR__.'/template/btUser.php');
    drawbtUser();
    drawBottom();
    return;
}

function checkbtUser()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']        = false;
        $_SESSION['errorMessage'] = array();

        foreach ($_SESSION['phab']['users'] as $user) {
            //check for missing post.
            if (!isset($_POST[$user['id'].'_username']) ||
                !isset($_POST[$user['id'].'_realname']) ||
                !isset($_POST[$user['id'].'_title']) ||
                !isset($_POST[$user['id'].'_email']) ||
                !isset($_POST[$user['id'].'_enabled'])) {
                //somehow the post doesnt have all the fields for all users.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Somehow the post doesnt have all the fields for all users - try again.";
                break;
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        foreach ($_SESSION['phab']['users'] as $user) {
            //check for required fields
            if (trim($_POST[$user['id'].'_username']) == "") {
                //username cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "User ".$user['id']." - Username can not be blank.";
            } else {
                //save username
                $_SESSION['phab']['users'][$user['id']]['username'] = trim($_POST[$user['id'].'_username']);
            }

            if (trim($_POST[$user['id'].'_realname']) == "") {
                //Realname cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "User ".$user['id']." - Realname can not be blank.";
            } else {
                //save Realname
                $_SESSION['phab']['users'][$user['id']]['realname'] = trim($_POST[$user['id'].'_realname']);
            }

            if (trim($_POST[$user['id'].'_email']) == "") {
                //Email cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "User ".$user['id']." - Email can not be blank.";
            } else {
                //save Email
                $_SESSION['phab']['users'][$user['id']]['email'] = trim($_POST[$user['id'].'_email']);
            }

            //optional fields
            if (trim($_POST[$user['id'].'_title']) != "") {
                //save Title
                $_SESSION['phab']['users'][$user['id']]['title'] = trim($_POST[$user['id'].'_title']);
            }

            if ($_POST[$user['id'].'_enabled'] == true || $_POST[$user['id'].'_enabled']
                == false) {
                //save Enabled if valid post is recieved.
                $_SESSION['phab']['users'][$user['id']]['enabled'] = $_POST[$user['id'].'_enabled'];
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['1'] = true;
        setCurrentStep(2);
    }
    return;
}

function loadbtProject()
{
    setPreviousStep(1);
    setNextStep(3);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['projects'])) {
        //move exported user into the import array
        mbt_getProjects();
        $_SESSION['phab']['projects'] = $_SESSION['mbt']['mantis_project_table'];
    }

    drawProgress("Projects");
    require(__DIR__.'/template/btProject.php');
    drawbtProject();
    drawBottom();
    return;
}

function checkbtProject()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']        = false;
        $_SESSION['errorMessage'] = array();

        foreach ($_SESSION['phab']['projects'] as $row) {
            //check for missing post.
            if (!isset($_POST[$row['id'].'_name']) ||
                !isset($_POST[$row['id'].'_description']) ||
                !isset($_POST[$row['id'].'_enabled'])) {
                //somehow the post doesnt have all the fields for all rows.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Somehow the post doesnt have all the fields for all Projects - try again.";
                break;
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        foreach ($_SESSION['phab']['projects'] as $row) {
            //check for required fields
            if (trim($_POST[$row['id'].'_name']) == "") {
                //Project Name cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Project ".$row['id']." - Project Name can not be blank.";
            } else {
                //save Project Name
                $_SESSION['phab']['projects'][$row['id']]['name'] = trim($_POST[$row['id'].'_name']);
            }

            if (trim($_POST[$row['id'].'_description']) == "") {
                //Description cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Project ".$row['id']." - Description can not be blank.";
            } else {
                //save Description
                $_SESSION['phab']['projects'][$row['id']]['description'] = trim($_POST[$row['id'].'_description']);
            }

            //optional fields
            if ($_POST[$row['id'].'_enabled'] == true || $_POST[$row['id'].'_enabled']
                == false) {
                //save Enabled if valid post is recieved.
                $_SESSION['phab']['projects'][$row['id']]['enabled'] = $_POST[$row['id'].'_enabled'];
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['2'] = true;
        setCurrentStep(3);
    }
    return;
}

function loadbtVersion()
{
    setPreviousStep(2);
    setNextStep(4);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['versions'])) {
        //move exported user into the import array
        mbt_getVersions();
        $_SESSION['phab']['versions'] = $_SESSION['mbt']['mantis_project_version_table'];
        //add support for phab project milestones
        foreach ($_SESSION['phab']['versions'] as $project) {
            foreach ($project as $row) {
                $_SESSION['phab']['versions'][$row['project_id']][$row['id']]['milestone']
                    = $row['released'];
            }
        }
    }

    drawProgress("Versions");
    require(__DIR__.'/template/btVersion.php');
    drawbtVersion();
    drawBottom();
    return;
}

function checkbtVersion()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']        = false;
        $_SESSION['errorMessage'] = array();

        foreach ($_SESSION['phab']['versions'] as $project) {
            foreach ($project as $row) {
                //check for missing post.
                if (!isset($_POST[$row['id'].'_version']) ||
                    !isset($_POST[$row['id'].'_milestone'])) {
                    //somehow the post doesnt have all the fields for all rows.
                    $_SESSION['error']          = true;
                    $_SESSION['errorMessage'][] = "Somehow the post doesnt have all the fields for all Versions - try again.";
                    break;
                }
            }

            //if there is an error stop here.
            if ($_SESSION['error']) {
                return;
            }

            foreach ($project as $row) {
                //check for required fields
                if (trim($_POST[$row['id'].'_version']) == "") {
                    //cant be blank.
                    $_SESSION['error']          = true;
                    $_SESSION['errorMessage'][] = "Version ".$row['id']." - Version can not be blank.";
                } else {
                    //save
                    $_SESSION['phab']['versions'][$row['project_id']][$row['id']]['version']
                        = trim($_POST[$row['id'].'_version']);
                }

                //optional fields
                if ($_POST[$row['id'].'_milestone'] == true || $_POST[$row['id'].'_milestone']
                    == false) {
                    //save milestone if valid post is recieved.
                    $_SESSION['phab']['versions'][$row['project_id']][$row['id']]['milestone']
                        = $_POST[$row['id'].'_milestone'];
                }
            }
        }
        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['3'] = true;
        setCurrentStep(4);
    }
    return;
}

function loadbtTag()
{
    setPreviousStep(3);
    setNextStep(5);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['tags'])) {
        //move exported user into the import array
        mbt_getTags();
        $_SESSION['phab']['tags'] = $_SESSION['mbt']['mantis_tag_table'];
    }

    drawProgress("Tags");
    require(__DIR__.'/template/btTag.php');
    drawbtTag();
    drawBottom();
    return;
}

function checkbtTag()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']        = false;
        $_SESSION['errorMessage'] = array();

        foreach ($_SESSION['phab']['tags'] as $row) {
            //check for missing post.
            if (!isset($_POST[$row['id'].'_name']) ||
                !isset($_POST[$row['id'].'_description'])) {
                //somehow the post doesnt have all the fields for all rows.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Somehow the post doesnt have all the fields for all Tags - try again.";
                break;
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        foreach ($_SESSION['phab']['tags'] as $row) {
            //check for required fields
            if (trim($_POST[$row['id'].'_name']) == "") {
                // Name cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Tag ".$row['id']." - Project Name can not be blank.";
            } else {
                //save
                $_SESSION['phab']['tags'][$row['id']]['name'] = trim($_POST[$row['id'].'_name']);
            }

            //optional fields
            if (trim($_POST[$row['id'].'_description']) != "") {
                //save
                $_SESSION['phab']['tags'][$row['id']]['description'] = trim($_POST[$row['id'].'_description']);
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['4'] = true;
        setCurrentStep(5);
    }
    return;
}

function loadbtCategory()
{
    setPreviousStep(4);
    setNextStep(6);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['category'])) {
        //move exported user into the import array
        mbt_getCategories();
        $_SESSION['phab']['category'] = $_SESSION['mbt']['mantis_category_table'];
    }

    drawProgress("Categories");
    require(__DIR__.'/template/btCategory.php');
    drawbtCategory();
    drawBottom();
    return;
}

function checkbtCategory()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']        = false;
        $_SESSION['errorMessage'] = array();

        foreach ($_SESSION['phab']['category'] as $row) {
            //check for missing post.
            if (!isset($_POST[$row['id'].'_name'])) {
                //somehow the post doesnt have all the fields for all rows.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Somehow the post doesnt have all the fields for all Categories - try again.";
                break;
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        foreach ($_SESSION['phab']['category'] as $row) {
            //check for required fields
            if (trim($_POST[$row['id'].'_name']) == "") {
                //Name cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Category ".$row['id']." - Category Name can not be blank.";
            } else {
                //save Name
                $_SESSION['phab']['category'][$row['id']]['name'] = trim($_POST[$row['id'].'_name']);
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['5'] = true;
        setCurrentStep(6);
    }
    return;
}

function loadbtCustomField()
{
    setPreviousStep(5);
    setNextStep(7);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['customFields'])) {
        //move exported user into the import array
        mbt_getCustomFields();
        $_SESSION['phab']['customFields'] = $_SESSION['mbt']['mantis_custom_field_table'];
    }

    drawProgress("Custom Fields");
    require(__DIR__.'/template/btCustomField.php');
    drawbtCustomField();
    drawBottom();
    return;
}

function checkbtCustomField()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']        = false;
        $_SESSION['errorMessage'] = array();

        foreach ($_SESSION['phab']['customFields'] as $row) {
            //check for missing post.
            if (!isset($_POST[$row['id'].'_name'])) {
                //somehow the post doesnt have all the fields for all rows.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Somehow the post doesnt have all the fields for all Custom Fields - try again.";
                break;
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        foreach ($_SESSION['phab']['customFields'] as $row) {
            //check for required fields
            if (trim($_POST[$row['id'].'_name']) == "") {
                //Name cant be blank.
                $_SESSION['error']          = true;
                $_SESSION['errorMessage'][] = "Custom Field ".$row['id']." - Custom Field Name can not be blank.";
            } else {
                //save Name
                $_SESSION['phab']['customFields'][$row['id']]['name'] = trim($_POST[$row['id'].'_name']);
            }
        }

        //if there is an error stop here.
        if ($_SESSION['error']) {
            return;
        }

        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['6'] = true;
        setCurrentStep(7);
    }
    return;
}

function loadbtBugs()
{
    setPreviousStep(6);
    setNextStep(8);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['phab']['tickets'])) {
        //move exported user into the import array
        mbt_getTickets();
        $_SESSION['phab']['tickets'] = $_SESSION['mbt']['mantis_bug_table'];
    }

    drawProgress("Tickets");
    require(__DIR__.'/template/btBugs.php');
    drawbtBugs();
    drawBottom();
    return;
}

function checkbtBugs()
{
    //checks and saves post and moves to next step
    if (isset($_POST['button']) && $_POST['button'] === "Save and Continue") {
        $_SESSION['error']                   = false;
        $_SESSION['errorMessage']            = array();
        //everything was ok - move to next step
        $_SESSION['wizardStep']['done']['7'] = true;
        setCurrentStep(10);
    }
    return;
}

//files

function loadbtFinish()
{
    setPreviousStep(8);
    setNextStep(10);
    drawTop("MantisBT Export");

    if (!isset($_SESSION['import'])) {
        //create the importing array for phab.
        mbt_getCustomFieldAnswers();
        mbt_getComments();
        phab_makeImport();
    }

    drawProgress("Review");
    require(__DIR__.'/template/btFinish.php');
    drawbtFinish();
    drawBottom();
    return;
}
