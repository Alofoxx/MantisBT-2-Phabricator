<?php
/**
 * Main functions for program logic.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function loadSteps(){
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

function setPreviousStep($step){
  $_SESSION['wizardStep']['previousStep'] = $step;
  return;
}

function setNextStep($step){
  $_SESSION['wizardStep']['nextStep'] = $step;
  return;
}

function setCurrentStep($step){
  $_SESSION['wizardStep']['currentStep'] = $step;
  return;
}

function runCurrentStep(){
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

function validateCurrentStep(){
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

function loadstartPage(){
  setPreviousStep(0);
  setNextStep(1);
  drawTop("Welcome");
  //drawProgress();
  require(__DIR__ . '/template/startPage.html');
  drawBottom();
  return;
}

function checkstartPage(){
  //checks post and moves to next step
  if(isset($_POST['button']) && $_POST['button'] === "Get Started"){
    setCurrentStep(1);
  }
  return;
}
